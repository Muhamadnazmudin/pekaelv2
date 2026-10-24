<?php

namespace App\Controllers;

use App\Models\MappingDudiModel;
use App\Models\SiswaModel;
use App\Models\DudiModel;
use App\Models\GuruModel;
use App\Models\KelasModel;
use App\Models\TahunModel;

class Mappingdudi extends BaseController
{
    protected MappingDudiModel $mappingModel;
    protected SiswaModel $siswaModel;
    protected DudiModel $dudiModel;
    protected GuruModel $guruModel;
    protected KelasModel $kelasModel;
    protected TahunModel $tahunModel;

    public function __construct()
    {
        $this->mappingModel = new MappingDudiModel();
        $this->siswaModel   = new SiswaModel();
        $this->dudiModel    = new DudiModel();
        $this->guruModel    = new GuruModel();
        $this->kelasModel   = new KelasModel();
        $this->tahunModel   = new TahunModel();
    }

    public function index()
    {
        $tahun = $this->tahunModel->getAktif();
        $tahunId = $tahun ? (int)$tahun->id : 0;

        // Ambil data penempatan aktif dengan relasi lengkap
        $mapping = $this->mappingModel->getAllWithRelations($tahunId);

        // Map hitungan riwayat perpindahan DUDI per siswa
        $historyRows = $this->db->table('riwayat_mapping_dudi')
            ->select('siswa_id, COUNT(*) as total_pindah')
            ->where('tahun_id', $tahunId)
            ->groupBy('siswa_id')
            ->get()->getResult();

        $historyMap = [];
        foreach ($historyRows as $hr) {
            $historyMap[(int)$hr->siswa_id] = (int)$hr->total_pindah;
        }

        // Siswa yang BELUM memiliki penempatan DUDI (hanya siswa ini yang bisa dipilih untuk DUDI baru)
        $siswaBelum = $this->siswaModel->select('siswa.id, siswa.nama, siswa.nisn, siswa.jenis_kelamin, siswa.id_kelas, kelas.nama_kelas')
            ->join('kelas', 'kelas.id = siswa.id_kelas')
            ->where('siswa.id_tahun', $tahunId)
            ->where('kelas.status_pkl', 'ya')
            ->groupStart()
                ->where('siswa.dudi_id IS NULL', null, false)
                ->orWhere('siswa.dudi_id', 0)
            ->groupEnd()
            ->orderBy('kelas.nama_kelas', 'ASC')
            ->orderBy('siswa.nama', 'ASC')
            ->findAll();

        // Daftar DUDI aktif dengan hitungan siswa yang sedang PKL di sana
        $dudiList = $this->db->table('dudi')
            ->select('dudi.*, COUNT(mapping_dudi.id) as total_siswa_dudi')
            ->join('mapping_dudi', "mapping_dudi.dudi_id = dudi.id AND mapping_dudi.tahun_id = {$tahunId}", 'left')
            ->where('dudi.status_kerjasama', 'aktif')
            ->groupBy('dudi.id')
            ->orderBy('dudi.nama_dudi', 'ASC')
            ->get()->getResult();

        $totalSiswaPkl = $this->db->table('siswa')
            ->join('kelas', 'kelas.id = siswa.id_kelas')
            ->where('siswa.id_tahun', $tahunId)
            ->where('kelas.status_pkl', 'ya')
            ->countAllResults();

        $data = [
            'title'          => 'Mapping Penempatan DUDI',
            'tahun'          => $tahun,
            'mapping'        => $mapping,
            'history_map'    => $historyMap,
            'dudi'           => $dudiList,
            'guru'           => $this->guruModel->getAktif(),
            'kelas_list'     => $this->kelasModel->getKelasPkl(),
            'siswa_belum'    => $siswaBelum,
            'stats'          => [
                'total_siswa'       => $totalSiswaPkl,
                'total_ditempatkan' => count($mapping),
                'total_belum'       => count($siswaBelum),
                'total_dudi'        => count($dudiList)
            ]
        ];

        return template('admin/mapping_dudi/index', $data);
    }

    /**
     * Simpan penempatan siswa baru ke DUDI
     * Aturan: Siswa yang sudah ditempatkan tidak bisa dipetakan lagi (harus lewat fitur Pindah DUDI)
     */
    public function simpan()
    {
        $tahun = $this->tahunModel->getAktif();
        if (!$tahun) {
            return redirect()->to(site_url('mappingdudi'))->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }

        $dudiId   = (int)($this->request->getPost('dudi_id') ?? 0);
        $guruId   = (int)($this->request->getPost('guru_id') ?? 0);
        $siswaIds = $this->request->getPost('siswa_id');

        if (!$dudiId || empty($siswaIds) || !is_array($siswaIds)) {
            return redirect()->to(site_url('mappingdudi'))->with('error', 'Pilih Mitra DUDI dan minimal satu siswa.');
        }

        $dudi = $this->dudiModel->find($dudiId);
        if (!$dudi) {
            return redirect()->to(site_url('mappingdudi'))->with('error', 'Mitra DUDI tidak ditemukan.');
        }

        $this->db->transStart();

        $suksesCount = 0;
        $dilewati = [];

        foreach ($siswaIds as $sId) {
            $sId = (int)$sId;
            if ($sId <= 0) continue;

            $siswa = $this->siswaModel->find($sId);
            if (!$siswa) continue;

            // Proteksi: Jika siswa sudah terpetakan di DUDI lain pada tahun aktif, lewati dan infokan
            if (!empty($siswa->dudi_id) && (int)$siswa->dudi_id > 0) {
                $dilewati[] = $siswa->nama;
                continue;
            }

            // Update siswa.dudi_id
            $this->siswaModel->update($sId, ['dudi_id' => $dudiId]);

            // Insert / Update mapping_dudi
            $cek = $this->mappingModel->where('siswa_id', $sId)->where('tahun_id', $tahun->id)->first();
            $mapData = [
                'siswa_id'  => $sId,
                'dudi_id'   => $dudiId,
                'guru_id'   => $guruId ?: null,
                'tahun_id'  => $tahun->id,
                'tahap_pkl' => 'PKL 1'
            ];

            if ($cek) {
                $this->mappingModel->update($cek->id, $mapData);
            } else {
                $this->mappingModel->insert($mapData);
            }

            $suksesCount++;
        }

        $this->db->transComplete();

        if ($suksesCount > 0) {
            $msg = "Berhasil menempatkan {$suksesCount} siswa ke {$dudi->nama_dudi} (PKL 1).";
            if (!empty($dilewati)) {
                $msg .= " Catatan: " . count($dilewati) . " siswa (" . implode(', ', array_slice($dilewati, 0, 3)) . ") dilewati karena sudah terpetakan di DUDI lain (gunakan fitur Pindah DUDI).";
            }
            return redirect()->to(site_url('mappingdudi'))->with('success', $msg);
        }

        return redirect()->to(site_url('mappingdudi'))->with('error', 'Tidak ada siswa yang berhasil ditempatkan. Pastikan siswa belum pernah dipetakan sebelumnya.');
    }

    /**
     * Fitur Pindah DUDI (Mutasi PKL)
     * Mencatat perpindahan (misal: PKL 1 di DUDI A -> PKL 2 di DUDI B) dan menyimpan riwayat historis
     */
    public function pindah()
    {
        $tahun = $this->tahunModel->getAktif();
        if (!$tahun) {
            return redirect()->to(site_url('mappingdudi'))->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }

        $mappingId    = (int)($this->request->getPost('mapping_id') ?? 0);
        $dudiTujuanId = (int)($this->request->getPost('dudi_tujuan_id') ?? 0);
        $alasan       = trim($this->request->getPost('alasan_pindah') ?? '');
        $guruId       = (int)($this->request->getPost('guru_id') ?? 0);

        if (!$mappingId || !$dudiTujuanId) {
            return redirect()->to(site_url('mappingdudi'))->with('error', 'Data penempatan dan DUDI tujuan wajib dipilih.');
        }

        $mapping = $this->mappingModel->find($mappingId);
        if (!$mapping) {
            return redirect()->to(site_url('mappingdudi'))->with('error', 'Data penempatan siswa tidak ditemukan.');
        }

        if ((int)$mapping->dudi_id === $dudiTujuanId) {
            return redirect()->to(site_url('mappingdudi'))->with('error', 'DUDI tujuan harus berbeda dengan DUDI saat ini.');
        }

        $siswa = $this->siswaModel->find($mapping->siswa_id);
        $dudiAsal = $this->dudiModel->find($mapping->dudi_id);
        $dudiTujuan = $this->dudiModel->find($dudiTujuanId);

        if (!$dudiTujuan) {
            return redirect()->to(site_url('mappingdudi'))->with('error', 'Mitra DUDI tujuan tidak valid.');
        }

        // Tentukan tahap PKL baru (PKL 1 -> PKL 2 -> PKL 3, dst.)
        $tahapLama = !empty($mapping->tahap_pkl) ? $mapping->tahap_pkl : 'PKL 1';
        $nomorTahap = (int)preg_replace('/[^0-9]/', '', $tahapLama);
        $tahapBaru = 'PKL ' . ($nomorTahap >= 1 ? ($nomorTahap + 1) : 2);

        $this->db->transStart();

        // 1. Simpan riwayat mutasi / pindah DUDI
        $this->db->table('riwayat_mapping_dudi')->insert([
            'siswa_id'         => $mapping->siswa_id,
            'dudi_asal_id'     => $mapping->dudi_id,
            'dudi_tujuan_id'   => $dudiTujuanId,
            'tahun_id'         => $tahun->id,
            'tahap_sebelumnya' => $tahapLama,
            'tahap_baru'       => $tahapBaru,
            'alasan_pindah'    => !empty($alasan) ? $alasan : 'Perpindahan penempatan PKL oleh operator',
            'tanggal_pindah'   => date('Y-m-d H:i:s'),
            'created_by'       => session()->get('username') ?: 'admin'
        ]);

        // 2. Perbarui mapping aktif di mapping_dudi
        $this->mappingModel->update($mapping->id, [
            'dudi_id'   => $dudiTujuanId,
            'tahap_pkl' => $tahapBaru,
            'guru_id'   => $guruId ?: $mapping->guru_id
        ]);

        // 3. Sinkronkan ke siswa.dudi_id
        $this->siswaModel->update($mapping->siswa_id, ['dudi_id' => $dudiTujuanId]);

        $this->db->transComplete();

        $namaSiswa = $siswa ? $siswa->nama : 'Siswa';
        $namaDudiAsal = $dudiAsal ? $dudiAsal->nama_dudi : 'DUDI Asal';
        $namaDudiTujuan = $dudiTujuan->nama_dudi;

        return redirect()->to(site_url('mappingdudi'))->with('success', "Berhasil memindahkan {$namaSiswa} dari {$namaDudiAsal} ({$tahapLama}) ke {$namaDudiTujuan} ({$tahapBaru}). Riwayat perpindahan tersimpan.");
    }

    /**
     * Reset / Batalkan penempatan siswa (mengembalikan siswa ke status belum ditempatkan)
     */
    public function hapus($id)
    {
        $row = $this->mappingModel->find($id);
        if ($row) {
            $siswa = $this->siswaModel->find($row->siswa_id);
            // Lepaskan dudi_id dari siswa
            $this->siswaModel->update($row->siswa_id, ['dudi_id' => null]);
            $this->mappingModel->delete($id);

            $nama = $siswa ? $siswa->nama : 'Siswa';
            return redirect()->to(site_url('mappingdudi'))->with('success', "Penempatan {$nama} berhasil direset. Siswa kini dapat dipetakan kembali ke mitra DUDI.");
        }

        return redirect()->to(site_url('mappingdudi'))->with('error', 'Data penempatan tidak ditemukan.');
    }

    /**
     * Ambil riwayat penempatan DUDI siswa (AJAX)
     */
    public function riwayat($siswaId)
    {
        $siswaId = (int)$siswaId;
        $siswa = $this->siswaModel->select('siswa.nama, siswa.nisn, kelas.nama_kelas')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->where('siswa.id', $siswaId)
            ->first();

        if (!$siswa) {
            return $this->response->setJSON(['status' => false, 'message' => 'Siswa tidak ditemukan.']);
        }

        $riwayat = $this->mappingModel->getRiwayatSiswa($siswaId);

        $cur = $this->mappingModel->select('mapping_dudi.*, dudi.nama_dudi, dudi.alamat as alamat_dudi')
            ->join('dudi', 'dudi.id = mapping_dudi.dudi_id')
            ->where('mapping_dudi.siswa_id', $siswaId)
            ->first();

        return $this->response->setJSON([
            'status'     => true,
            'siswa'      => $siswa,
            'penempatan' => $cur,
            'riwayat'    => $riwayat
        ]);
    }

    /**
     * Pencarian DUDI secara cepat & dinamis (AJAX)
     */
    public function search_dudi()
    {
        $q = trim($this->request->getGet('q') ?? '');
        $tahun = $this->tahunModel->getAktif();
        $tahunId = $tahun ? (int)$tahun->id : 0;

        $builder = $this->db->table('dudi')
            ->select('dudi.id, dudi.nama_dudi, dudi.bidang_usaha, dudi.alamat, dudi.desa_kelurahan, dudi.kecamatan, dudi.kabupaten_kota, dudi.nama_pic, dudi.no_hp_pic, COUNT(mapping_dudi.id) as total_siswa')
            ->join('mapping_dudi', "mapping_dudi.dudi_id = dudi.id AND mapping_dudi.tahun_id = {$tahunId}", 'left')
            ->where('dudi.status_kerjasama', 'aktif');

        if (!empty($q)) {
            $builder->groupStart()
                ->like('dudi.nama_dudi', $q)
                ->orLike('dudi.alamat', $q)
                ->orLike('dudi.bidang_usaha', $q)
                ->orLike('dudi.kecamatan', $q)
            ->groupEnd();
        }

        $results = $builder->groupBy('dudi.id')
            ->orderBy('dudi.nama_dudi', 'ASC')
            ->limit(30)
            ->get()->getResult();

        return $this->response->setJSON([
            'status'  => true,
            'results' => $results
        ]);
    }
}
