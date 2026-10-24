<?php

namespace App\Models;

use CodeIgniter\Model;
use PDO;
use Exception;

class DapodikModel extends Model
{
    private ?PDO $pdo = null;
    private array $config = [
        'host'     => '127.0.0.1',
        'port'     => 54532,
        'database' => 'pendataan',
        'username' => 'postgres',
        'password' => ''
    ];

    public function __construct()
    {
        parent::__construct();
        $this->resolveDapodikCredentials();
    }

    /**
     * Otomatis mendeteksi password PostgreSQL Dapodik dari modul resmi Dapodik lokal
     */
    private function resolveDapodikCredentials(): void
    {
        $soPath = "C:/Program Files (x86)/Dapodik/webserver/modules/mod_sied.so";
        $pharPath = "C:/Program Files (x86)/Dapodik/dataweb/synch_dikdas/includes/js/defuse-crypto.phar";

        if (file_exists($soPath) && file_exists($pharPath)) {
            try {
                if (!class_exists('Defuse\Crypto\Crypto')) {
                    require_once $pharPath;
                }
                $satad = file_get_contents($soPath);
                $aynatad = explode("==", $satad);
                if (count($aynatad) >= 2) {
                    $icnuk = \Defuse\Crypto\Key::loadFromAsciiSafeString($aynatad[0]);
                    $this->config['password'] = \Defuse\Crypto\Crypto::decrypt($aynatad[1], $icnuk, false);
                }
            } catch (Exception $e) {
                log_message('error', 'Gagal decrypt password Dapodik: ' . $e->getMessage());
            }
        }
    }

    /**
     * Membuka koneksi READ-ONLY ke database Dapodik lokal
     */
    public function getConnection(): ?PDO
    {
        if ($this->pdo !== null) {
            return $this->pdo;
        }

        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s',
            $this->config['host'],
            $this->config['port'],
            $this->config['database']
        );

        try {
            $this->pdo = new PDO($dsn, $this->config['username'], $this->config['password'], [
                PDO::ATTR_TIMEOUT          => 3,
                PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            // Set session readonly di PostgreSQL untuk jaminan mutlak keamanan Dapodik
            $this->pdo->exec("SET SESSION CHARACTERISTICS AS TRANSACTION READ ONLY");

            return $this->pdo;
        } catch (Exception $e) {
            log_message('error', 'Koneksi Dapodik gagal: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Tes koneksi dan ringkasan data dari Dapodik
     */
    public function testConnection(): array
    {
        $pdo = $this->getConnection();
        if (!$pdo) {
            return [
                'success' => false,
                'message' => 'Tidak dapat terhubung ke database Dapodik lokal (Port 54532).'
            ];
        }

        try {
            $sekolah = $pdo->query("SELECT nama, npsn FROM sekolah LIMIT 1")->fetch();
            $totalSiswa = $pdo->query("SELECT COUNT(*) FROM peserta_didik WHERE soft_delete = 0")->fetchColumn();
            $totalRombel = $pdo->query("SELECT COUNT(*) FROM rombongan_belajar WHERE soft_delete = 0")->fetchColumn();
            $totalGuru = $pdo->query("SELECT COUNT(*) FROM ptk WHERE soft_delete = 0")->fetchColumn();
            $totalDudi = $pdo->query("SELECT COUNT(*) FROM dudi")->fetchColumn();

            return [
                'success'       => true,
                'message'       => 'Berhasil terhubung ke Dapodik lokal (Mode Read-Only).',
                'sekolah'       => $sekolah['nama'] ?? '-',
                'npsn'          => trim($sekolah['npsn'] ?? ''),
                'total_siswa'   => (int)$totalSiswa,
                'total_rombel'  => (int)$totalRombel,
                'total_guru'    => (int)$totalGuru,
                'total_dudi'    => (int)$totalDudi
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error membaca data Dapodik: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Tarik Profil Sekolah dari Dapodik
     */
    public function tarikSekolah(): array
    {
        $pdo = $this->getConnection();
        if (!$pdo) return ['success' => false, 'message' => 'Koneksi Dapodik gagal'];

        $dapo = $pdo->query("SELECT * FROM sekolah LIMIT 1")->fetch();
        if (!$dapo) return ['success' => false, 'message' => 'Data sekolah tidak ditemukan di Dapodik'];

        // Cari Kepala Sekolah dari PTK jika ada
        $kepsek = $pdo->query("
            SELECT ptk.nama, ptk.nip 
            FROM ptk 
            JOIN tugas_tambahan ON tugas_tambahan.ptk_id = ptk.ptk_id 
            WHERE tugas_tambahan.jabatan_ptk_id = 2 
            LIMIT 1
        ")->fetch();

        $sekolahModel = new SekolahModel();
        $profil = $sekolahModel->getProfil();

        $updateData = [
            'nama_sekolah'   => $dapo['nama'] ?? $profil->nama_sekolah,
            'npsn'           => trim($dapo['npsn'] ?? $profil->npsn),
            'alamat'         => trim(($dapo['alamat_jalan'] ?? '') . ' ' . ($dapo['desa_kelurahan'] ?? '')),
            'kepala_sekolah' => $kepsek['nama'] ?? $profil->kepala_sekolah,
            'nip_kepsek'     => $kepsek['nip'] ?? $profil->nip_kepsek
        ];

        $sekolahModel->update($profil->id, $updateData);

        return [
            'success' => true,
            'message' => 'Data profil sekolah berhasil ditarik dari Dapodik.',
            'data'    => $updateData
        ];
    }

    /**
     * Tarik Data Jurusan dari Dapodik
     */
    public function tarikJurusan(): array
    {
        $pdo = $this->getConnection();
        if (!$pdo) return ['success' => false, 'message' => 'Koneksi Dapodik gagal'];

        $stmt = $pdo->query("SELECT jurusan_sp_id, nama_jurusan_sp, jurusan_id FROM jurusan_sp WHERE soft_delete = 0");
        $jurusanModel = new JurusanModel();

        $inserted = 0;
        $updated = 0;

        while ($row = $stmt->fetch()) {
            $namaJurusan = trim($row['nama_jurusan_sp']);
            $kodeJurusan = trim($row['jurusan_id'] ?? '');

            // Buat singkatan cerdas dari nama jurusan (misal: Rekayasa Perangkat Lunak -> RPL)
            $words = preg_split('/\s+/', $namaJurusan);
            $singkatan = '';
            foreach ($words as $w) {
                if (strlen($w) > 0 && ctype_alnum($w[0])) {
                    $singkatan .= strtoupper($w[0]);
                }
            }

            $cek = $jurusanModel->where('nama_jurusan', $namaJurusan)->first();
            if ($cek) {
                $jurusanModel->update($cek->id, [
                    'kode_jurusan' => $kodeJurusan ?: $cek->kode_jurusan,
                    'singkatan'    => $singkatan ?: $cek->singkatan
                ]);
                $updated++;
            } else {
                $jurusanModel->insert([
                    'nama_jurusan' => $namaJurusan,
                    'kode_jurusan' => $kodeJurusan ?: $singkatan,
                    'singkatan'    => $singkatan
                ]);
                $inserted++;
            }
        }

        return [
            'success'  => true,
            'message'  => "Berhasil sinkron Jurusan: $inserted data baru, $updated diperbarui.",
            'inserted' => $inserted,
            'updated'  => $updated
        ];
    }

    /**
     * Tarik Data Kelas (Rombel) dari Dapodik
     */
    public function tarikKelas(): array
    {
        $pdo = $this->getConnection();
        if (!$pdo) return ['success' => false, 'message' => 'Koneksi Dapodik gagal'];

        $sql = "
            SELECT r.rombongan_belajar_id, r.nama, r.tingkat_pendidikan_id, j.nama_jurusan_sp
            FROM rombongan_belajar r
            LEFT JOIN jurusan_sp j ON j.jurusan_sp_id = r.jurusan_sp_id
            WHERE r.soft_delete = 0 AND r.jenis_rombel = 1
            ORDER BY r.tingkat_pendidikan_id DESC, r.nama ASC
        ";

        $stmt = $pdo->query($sql);
        $kelasModel = new KelasModel();
        $jurusanModel = new JurusanModel();

        $inserted = 0;
        $updated = 0;

        while ($row = $stmt->fetch()) {
            $namaKelas = trim($row['nama']);
            $tingkatRaw = (int)$row['tingkat_pendidikan_id'];
            
            // Konversi tingkat pendidikan: 10->X, 11->XI, 12->XII, 13->XII
            $tingkat = 'XII';
            if ($tingkatRaw == 10) $tingkat = 'X';
            elseif ($tingkatRaw == 11) $tingkat = 'XI';
            elseif ($tingkatRaw >= 12) $tingkat = 'XII';

            // Hitung jumlah siswa aktif di rombel ini
            $rombelId = $row['rombongan_belajar_id'];
            $qSiswa = $pdo->prepare("SELECT COUNT(*) FROM anggota_rombel WHERE rombongan_belajar_id = :rid AND soft_delete = 0");
            $qSiswa->execute([':rid' => $rombelId]);
            $jmlSiswa = (int)$qSiswa->fetchColumn();

            // Cocokkan jurusan
            $jurusanId = 0;
            if (!empty($row['nama_jurusan_sp'])) {
                $jRow = $jurusanModel->where('nama_jurusan', trim($row['nama_jurusan_sp']))->first();
                if ($jRow) {
                    $jurusanId = $jRow->id;
                }
            }

            // Kelas XII otomatis aktif status PKL ya
            $statusPkl = ($tingkat === 'XII') ? 'ya' : 'tidak';

            $cek = $kelasModel->where('nama_kelas', $namaKelas)->first();
            if ($cek) {
                $kelasModel->update($cek->id, [
                    'tingkat'      => $tingkat,
                    'status_pkl'   => $statusPkl,
                    'jumlah_siswa' => $jmlSiswa,
                    'jurusan_id'   => $jurusanId ?: $cek->jurusan_id
                ]);
                $updated++;
            } else {
                $kelasModel->insert([
                    'nama_kelas'   => $namaKelas,
                    'tingkat'      => $tingkat,
                    'status_pkl'   => $statusPkl,
                    'jumlah_siswa' => $jmlSiswa,
                    'jurusan_id'   => $jurusanId
                ]);
                $inserted++;
            }
        }

        return [
            'success'  => true,
            'message'  => "Berhasil sinkron Kelas: $inserted kelas baru, $updated diperbarui.",
            'inserted' => $inserted,
            'updated'  => $updated
        ];
    }

    /**
     * Tarik Data Siswa dari Dapodik
     */
    public function tarikSiswa(int $tahunId, bool $hanyaTingkat12 = true): array
    {
        $pdo = $this->getConnection();
        if (!$pdo) return ['success' => false, 'message' => 'Koneksi Dapodik gagal'];

        $tingkatFilter = $hanyaTingkat12 ? "AND r.tingkat_pendidikan_id >= 12" : "";

        $sql = "
            SELECT p.nama, p.nisn, p.nik, p.tempat_lahir, p.tanggal_lahir, p.jenis_kelamin, 
                   p.alamat_jalan, p.desa_kelurahan, r.nama as nama_kelas, r.tingkat_pendidikan_id
            FROM anggota_rombel ar
            JOIN peserta_didik p ON p.peserta_didik_id = ar.peserta_didik_id
            JOIN rombongan_belajar r ON r.rombongan_belajar_id = ar.rombongan_belajar_id
            WHERE ar.soft_delete = 0 AND p.soft_delete = 0 AND r.soft_delete = 0
            $tingkatFilter
            ORDER BY r.nama ASC, p.nama ASC
        ";

        $stmt = $pdo->query($sql);
        $siswaModel = new SiswaModel();
        $kelasModel = new KelasModel();

        // Siapkan lookup map kelas
        $kelasList = $kelasModel->findAll();
        $kelasMap = [];
        foreach ($kelasList as $k) {
            $kelasMap[trim(strtoupper($k->nama_kelas))] = $k->id;
        }

        $inserted = 0;
        $updated = 0;

        while ($row = $stmt->fetch()) {
            $nama = trim($row['nama']);
            $nisn = trim($row['nisn'] ?? '');
            $namaKelas = trim(strtoupper($row['nama_kelas']));
            $kelasId = $kelasMap[$namaKelas] ?? 0;

            $dataSiswa = [
                'nisn'              => $nisn,
                'nama'              => $nama,
                'tempat_lahir'      => trim($row['tempat_lahir'] ?? ''),
                'tanggal_lahir'     => !empty($row['tanggal_lahir']) ? $row['tanggal_lahir'] : null,
                'jenis_kelamin'     => in_array($row['jenis_kelamin'], ['L', 'P']) ? $row['jenis_kelamin'] : 'L',
                'alamat'            => trim(($row['alamat_jalan'] ?? '') . ' ' . ($row['desa_kelurahan'] ?? '')),
                'id_kelas'          => $kelasId,
                'id_tahun'          => $tahunId,
                'status_verifikasi' => 'diterima'
            ];

            // Cek berdasarkan NISN atau Nama + Kelas
            $cek = null;
            if (!empty($nisn)) {
                $cek = $siswaModel->where('nisn', $nisn)->where('id_tahun', $tahunId)->first();
            }
            if (!$cek) {
                $cek = $siswaModel->where('nama', $nama)->where('id_tahun', $tahunId)->first();
            }

            if ($cek) {
                $siswaModel->update($cek->id, $dataSiswa);
                $updated++;
            } else {
                $siswaModel->insert($dataSiswa);
                $inserted++;
            }
        }

        return [
            'success'  => true,
            'message'  => "Berhasil sinkron Siswa: $inserted siswa baru, $updated diperbarui.",
            'inserted' => $inserted,
            'updated'  => $updated
        ];
    }

    /**
     * Tarik Data Relasi DUDI & MoU/PKS dari Dapodik
     */
    public function tarikDudi(): array
    {
        $pdo = $this->getConnection();
        if (!$pdo) return ['success' => false, 'message' => 'Koneksi Dapodik gagal'];

        $sql = "
            SELECT d.nama as nama_dudi, d.alamat_jalan, d.desa_kelurahan, d.nomor_telepon,
                   m.nomor_mou, m.judul_mou, m.nama_bidang_usaha, m.contact_person, m.telp_cp
            FROM dudi d
            LEFT JOIN mou m ON m.dudi_id = d.dudi_id AND m.soft_delete = 0
            WHERE d.soft_delete = 0
            ORDER BY d.nama ASC
        ";

        $stmt = $pdo->query($sql);
        $dudiModel = new DudiModel();

        $inserted = 0;
        $updated = 0;

        while ($row = $stmt->fetch()) {
            $namaDudi = trim($row['nama_dudi']);
            if (empty($namaDudi)) continue;

            $dataDudi = [
                'nama_dudi'        => $namaDudi,
                'bidang_usaha'     => trim($row['nama_bidang_usaha'] ?? 'Mitra PKL'),
                'alamat'           => trim($row['alamat_jalan'] ?? ''),
                'desa_kelurahan'   => trim($row['desa_kelurahan'] ?? ''),
                'nomor_mou'        => trim($row['nomor_mou'] ?? ''),
                'judul_pks'        => trim($row['judul_mou'] ?? ''),
                'nama_pic'         => trim($row['contact_person'] ?? ''),
                'no_hp_pic'        => trim($row['telp_cp'] ?? $row['nomor_telepon'] ?? ''),
                'status_kerjasama' => 'aktif'
            ];

            $cek = $dudiModel->where('nama_dudi', $namaDudi)->first();
            if ($cek) {
                $dudiModel->update($cek->id, $dataDudi);
                $updated++;
            } else {
                $dudiModel->insert($dataDudi);
                $inserted++;
            }
        }

        return [
            'success'  => true,
            'message'  => "Berhasil sinkron DUDI: $inserted DUDI baru, $updated diperbarui.",
            'inserted' => $inserted,
            'updated'  => $updated
        ];
    }

    /**
     * Tarik Data Guru (PTK) dari Dapodik
     */
    public function tarikGuru(): array
    {
        $pdo = $this->getConnection();
        if (!$pdo) return ['success' => false, 'message' => 'Koneksi Dapodik gagal'];

        $sql = "
            SELECT nama, nip, jenis_ptk_id 
            FROM ptk 
            WHERE soft_delete = 0 
            ORDER BY nama ASC
        ";

        $stmt = $pdo->query($sql);
        $guruModel = new GuruModel();

        $inserted = 0;
        $updated = 0;

        while ($row = $stmt->fetch()) {
            $nama = trim($row['nama']);
            $nip = trim($row['nip'] ?? '');

            // Deteksi jenis guru: jenis_ptk_id = 4 (Guru BK), 3 (Guru Mapel / Produktif)
            $jenisGuru = 'produktif';

            $dataGuru = [
                'nama_guru'  => $nama,
                'nip'        => $nip,
                'status'     => 'aktif',
                'jenis_guru' => $jenisGuru
            ];

            $cek = null;
            if (!empty($nip) && $nip !== '-' && strlen($nip) > 5) {
                $cek = $guruModel->where('nip', $nip)->first();
            }
            if (!$cek) {
                $cek = $guruModel->where('nama_guru', $nama)->first();
            }

            if ($cek) {
                $guruModel->update($cek->id, $dataGuru);
                $updated++;
            } else {
                $guruModel->insert($dataGuru);
                $inserted++;
            }
        }

        return [
            'success'  => true,
            'message'  => "Berhasil sinkron Guru: $inserted guru baru, $updated diperbarui.",
            'inserted' => $inserted,
            'updated'  => $updated
        ];
    }
}
