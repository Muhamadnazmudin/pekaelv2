<?php

namespace App\Controllers;

use App\Models\PengaturanModel;
use App\Models\TahunModel;

class Pengaturan extends BaseController
{
    protected PengaturanModel $pengaturanModel;
    protected TahunModel $tahunModel;

    public function __construct()
    {
        $this->pengaturanModel = new PengaturanModel();
        $this->tahunModel      = new TahunModel();
    }

    public function index()
    {
        $tahun = $this->tahunModel->getAktif();
        $db = \Config\Database::connect();

        // Daftar tabel yang dapat dibersihkan secara aman
        $tablesConfig = [
            'operasional' => [
                'label' => 'Data Operasional & Alokasi PKL',
                'items' => [
                    'siswa' => [
                        'nama'      => 'Data Siswa Peserta PKL',
                        'deskripsi' => 'Seluruh data siswa, NISN, status, dan riwayat penempatan PKL.',
                        'icon'      => 'fas fa-user-graduate',
                        'badge'     => 'primary'
                    ],
                    'absensi_pkl' => [
                        'nama'      => 'Presensi & Kehadiran Siswa',
                        'deskripsi' => 'Seluruh riwayat presensi selfie GPS dan waktu clock-in/out.',
                        'icon'      => 'fas fa-camera',
                        'badge'     => 'primary'
                    ],
                    'jurnal_pkl' => [
                        'nama'      => 'Jurnal Kegiatan Siswa PKL',
                        'deskripsi' => 'Seluruh arsip laporan kegiatan harian dan evaluasi pembimbing.',
                        'icon'      => 'fas fa-book-reader',
                        'badge'     => 'primary'
                    ],
                    'mapping_dudi' => [
                        'nama'      => 'Mapping Penempatan Siswa di DUDI',
                        'deskripsi' => 'Data relasi penempatan siswa di industri/mitra PKL.',
                        'icon'      => 'fas fa-map-marked-alt',
                        'badge'     => 'primary'
                    ],
                    'pembagian_jam' => [
                        'nama'      => 'Pembagian Jam Mengajar PKL (46 JP)',
                        'deskripsi' => 'Alokasi jam mengajar per rombel untuk guru pembimbing.',
                        'icon'      => 'fas fa-clock',
                        'badge'     => 'primary'
                    ],
                    'pembimbing_pkl' => [
                        'nama'      => 'Kuota Pembimbing (Metode Koefisien)',
                        'deskripsi' => 'Alokasi kuota siswa bimbingan hasil hitung koefisien.',
                        'icon'      => 'fas fa-user-check',
                        'badge'     => 'primary'
                    ],
                    'pembimbing_pkl_perkelas' => [
                        'nama'      => 'Kuota Pembimbing (Metode Proporsional Kelas)',
                        'deskripsi' => 'Alokasi kuota pembimbing yang dihitung per rombel kelas.',
                        'icon'      => 'fas fa-chalkboard-teacher',
                        'badge'     => 'primary'
                    ],
                    'distribusi_manual' => [
                        'nama'      => 'Distribusi Siswa Bimbingan (Metode Koefisien)',
                        'deskripsi' => 'Penetapan nama siswa ke guru pembimbing metode koefisien.',
                        'icon'      => 'fas fa-tasks',
                        'badge'     => 'primary'
                    ],
                    'distribusi_pkl' => [
                        'nama'      => 'Distribusi Siswa Bimbingan (Metode Proporsional)',
                        'deskripsi' => 'Penetapan nama siswa ke guru pembimbing metode proporsional.',
                        'icon'      => 'fas fa-random',
                        'badge'     => 'primary'
                    ],
                    'koefisien_pkl' => [
                        'nama'      => 'Hasil Perhitungan Angka Koefisien',
                        'deskripsi' => 'Riwayat angka koefisien per tahun ajaran.',
                        'icon'      => 'fas fa-calculator',
                        'badge'     => 'primary'
                    ],
                    'log_cek' => [
                        'nama'      => 'Log Riwayat Cek Penempatan Siswa',
                        'deskripsi' => 'Riwayat akses portal mandiri pencarian status PKL.',
                        'icon'      => 'fas fa-history',
                        'badge'     => 'info'
                    ],
                    'pusat_unduhan' => [
                        'nama'      => 'Berkas & Dokumen Pusat Unduhan',
                        'deskripsi' => 'File lampiran atau panduan yang telah diunggah.',
                        'icon'      => 'fas fa-file-download',
                        'badge'     => 'info'
                    ],
                ]
            ],
            'master' => [
                'label' => 'Data Master & Referensi Sekolah',
                'items' => [
                    'dudi' => [
                        'nama'      => 'Data Mitra DUDI & Industri MoU',
                        'deskripsi' => 'Daftar perusahaan mitra, alamat, pimpinan, dan kontak.',
                        'icon'      => 'fas fa-building',
                        'badge'     => 'warning'
                    ],
                    'guru' => [
                        'nama'      => 'Data Guru & Tenaga Pendidik',
                        'deskripsi' => 'Daftar guru pembimbing, NIP, gelar, dan status PTK.',
                        'icon'      => 'fas fa-user-tie',
                        'badge'     => 'warning'
                    ],
                    'kelas' => [
                        'nama'      => 'Data Kelas / Rombel',
                        'deskripsi' => 'Daftar rombongan belajar dan status kelas sasaran PKL.',
                        'icon'      => 'fas fa-door-open',
                        'badge'     => 'warning'
                    ],
                    'jurusan' => [
                        'nama'      => 'Data Program Keahlian / Jurusan',
                        'deskripsi' => 'Daftar kompetensi keahlian kejuruan dan kepala program.',
                        'icon'      => 'fas fa-graduation-cap',
                        'badge'     => 'warning'
                    ],
                    'mata_pelajaran' => [
                        'nama'      => 'Data Mata Pelajaran',
                        'deskripsi' => 'Mata pelajaran kurikulum PKL SMK.',
                        'icon'      => 'fas fa-book',
                        'badge'     => 'warning'
                    ],
                    'kelompok_mapel' => [
                        'nama'      => 'Kelompok Mapel',
                        'deskripsi' => 'Struktur pengelompokan mapel.',
                        'icon'      => 'fas fa-layer-group',
                        'badge'     => 'warning'
                    ],
                ]
            ],
            'users' => [
                'label' => 'Akun Pengguna & Operator Tambahan',
                'items' => [
                    'users' => [
                        'nama'      => 'Akun Operator Tambahan',
                        'deskripsi' => 'Hapus operator tambahan. <b>Akun Administrator Utama (mengadmin / ID 1) dijamin 100% AMAN</b>.',
                        'icon'      => 'fas fa-users-cog',
                        'badge'     => 'danger'
                    ],
                ]
            ]
        ];

        // Ambil hitungan baris terkini untuk setiap tabel
        $tablesData = [];
        $grandTotalRows = 0;
        foreach ($tablesConfig as $catKey => $cat) {
            $tablesData[$catKey] = [
                'label' => $cat['label'],
                'items' => []
            ];
            foreach ($cat['items'] as $tbl => $meta) {
                try {
                    if ($tbl === 'users') {
                        $count = $db->table('users')->where('id !=', 1)->where('username !=', 'mengadmin')->countAllResults();
                    } else {
                        $count = $db->table($tbl)->countAllResults();
                    }
                } catch (\Throwable $e) {
                    $count = 0;
                }
                $meta['count'] = $count;
                $tablesData[$catKey]['items'][$tbl] = $meta;
                $grandTotalRows += $count;
            }
        }

        $data['title']          = 'Pengaturan Sistem & Pemeliharaan Database';
        $data['tahun']          = $tahun;
        $data['jam_pkl']        = $this->pengaturanModel->getJamPkl();
        $data['nama_app']       = $this->pengaturanModel->getSetting('nama_aplikasi', 'PEKAEL');
        $data['metode_aktif']   = $this->pengaturanModel->getMetode();
        $data['tablesData']     = $tablesData;
        $data['grandTotalRows'] = $grandTotalRows;

        return template('admin/pengaturan/index', $data);
    }

    public function simpan()
    {
        $jamPkl  = (int)($this->request->getPost('jam_pkl') ?? 46);
        $namaApp = trim($this->request->getPost('nama_aplikasi') ?? 'PEKAEL');
        $metode  = $this->request->getPost('metode_perhitungan');

        $tahun = $this->tahunModel->getAktif();
        $this->pengaturanModel->setJamPkl($jamPkl, $tahun ? $tahun->id : null);
        $this->pengaturanModel->setSetting('nama_aplikasi', $namaApp);

        if (in_array($metode, ['koefisien', 'proporsional'])) {
            $this->pengaturanModel->setMetode($metode);
        }

        return redirect()->to(site_url('pengaturan'))->with('success', 'Pengaturan parameter sistem & metode perhitungan berhasil disimpan.');
    }

    /**
     * Switch Metode Perhitungan PKL (Koefisien vs Proporsional)
     */
    public function set_metode()
    {
        $metode = $this->request->getPost('metode') ?: $this->request->getGet('metode');
        if (!in_array($metode, ['koefisien', 'proporsional'])) {
            $metode = 'koefisien';
        }

        $this->pengaturanModel->setMetode($metode);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'success',
                'metode' => $metode
            ]);
        }

        $label = ($metode === 'proporsional') ? 'Proporsional Kelas' : 'Koefisien Konversi';
        return redirect()->back()->with('success', "Metode perhitungan JJM PKL berhasil diubah ke: <strong>{$label}</strong>.");
    }

    /**
     * Switch Tema Tampilan (Light / Dark Mode) dari Topbar / Sidebar
     */
    public function set_theme()
    {
        $theme = $this->request->getPost('theme') ?: $this->request->getGet('theme');
        if (!in_array($theme, ['dark', 'light'])) {
            $theme = 'light';
        }

        $this->pengaturanModel->setSetting('tema_aplikasi', $theme);
        setcookie('pekael_theme', $theme, time() + (365 * 86400), '/');

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'success',
                'theme'  => $theme
            ]);
        }

        return redirect()->back()->with('success', "Tema tampilan berhasil diubah ke: <strong>" . strtoupper($theme) . " MODE</strong>.");
    }

    /**
     * Fitur Pembersihan Database Terpilih (Reset Data Tanpa phpMyAdmin)
     */
    public function reset_data()
    {
        $tables = $this->request->getPost('tables');
        if (empty($tables) || !is_array($tables)) {
            return redirect()->to(site_url('pengaturan'))->with('error', 'Silakan centang minimal satu tabel yang ingin dibersihkan.');
        }

        $allowedTables = [
            'siswa', 'mapping_dudi', 'pembagian_jam', 'pembimbing_pkl', 
            'pembimbing_pkl_perkelas', 'distribusi_manual', 'distribusi_pkl', 
            'koefisien_pkl', 'log_cek', 'pusat_unduhan', 'dudi', 
            'guru', 'kelas', 'jurusan', 'mata_pelajaran', 'kelompok_mapel', 
            'absensi_pkl', 'jurnal_pkl', 'users'
        ];

        $db = \Config\Database::connect();
        $cleared = [];
        $totalRows = 0;

        try {
            $db->query('SET FOREIGN_KEY_CHECKS = 0');

            foreach ($tables as $t) {
                if (!in_array($t, $allowedTables)) {
                    continue;
                }

                if ($t === 'users') {
                    // PENGAMAN KRUSIAL: Administrator Utama (id=1 / username='mengadmin') TIDAK AKAN DILIBAS
                    $countBefore = $db->table('users')->where('id !=', 1)->where('username !=', 'mengadmin')->countAllResults();
                    $db->query("DELETE FROM users WHERE id != 1 AND username != 'mengadmin'");
                    $totalRows += $countBefore;
                    $cleared[] = "Operator Tambahan ($countBefore baris)";
                } else {
                    $countBefore = $db->table($t)->countAllResults();
                    $db->query("TRUNCATE TABLE `$t`");
                    $totalRows += $countBefore;
                    $cleared[] = "$t ($countBefore baris)";
                }
            }

            $db->query('SET FOREIGN_KEY_CHECKS = 1');

            $msg = 'Berhasil membersihkan ' . count($cleared) . ' tabel terpilih (Total: ' . number_format($totalRows) . ' baris data dihapus). Akun Administrator Utama tetap aman.';
            return redirect()->to(site_url('pengaturan'))->with('success', $msg);

        } catch (\Throwable $e) {
            $db->query('SET FOREIGN_KEY_CHECKS = 1');
            return redirect()->to(site_url('pengaturan'))->with('error', 'Terjadi kesalahan saat membersihkan database: ' . $e->getMessage());
        }
    }
}
