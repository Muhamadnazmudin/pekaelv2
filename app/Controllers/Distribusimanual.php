<?php

namespace App\Controllers;

use App\Models\DistribusiManualModel;
use App\Models\GuruModel;
use App\Models\TahunModel;

class Distribusimanual extends BaseController
{
    protected DistribusiManualModel $distribusiModel;
    protected GuruModel $guruModel;
    protected TahunModel $tahunModel;

    public function __construct()
    {
        $this->distribusiModel = new DistribusiManualModel();
        $this->guruModel       = new GuruModel();
        $this->tahunModel      = new TahunModel();
    }

    public function index()
    {
        $tahun = $this->tahunModel->getAktif();
        $tahunId = $tahun ? $tahun->id : 0;

        $data['title'] = 'Distribusi Siswa (Metode Koefisien)';
        $data['tahun'] = $tahun;

        // Guru yang memiliki kuota pembimbing di tahun ini
        $pembimbing = $this->db->table('pembimbing_pkl')
            ->select('guru.id as guru_id, guru.nama_guru, guru.nip, SUM(pembimbing_pkl.jumlah_bimbingan) as kuota, SUM(pembimbing_pkl.total_jam) as total_jam')
            ->join('guru', 'guru.id = pembimbing_pkl.guru_id')
            ->where('pembimbing_pkl.tahun_id', $tahunId)
            ->groupBy('guru.id, guru.nama_guru, guru.nip')
            ->orderBy('guru.nama_guru', 'ASC')
            ->get()->getResult();

        // Hitung realisasi siswa terbimbing saat ini
        foreach ($pembimbing as &$p) {
            $p->terbimbing = $this->db->table('distribusi_manual')
                ->where('guru_id', $p->guru_id)
                ->where('tahun_id', $tahunId)
                ->countAllResults();
        }

        $data['pembimbing'] = $pembimbing;
        $data['distribusi'] = $this->distribusiModel->getAllWithRelations($tahunId);

        return template('admin/distribusi_manual/index', $data);
    }

    public function mapping($guruId)
    {
        $tahun = $this->tahunModel->getAktif();
        if (!$tahun) {
            return redirect()->to(site_url('tahun'))->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }

        $guru = $this->guruModel->find($guruId);
        if (!$guru) {
            return redirect()->to(site_url('distribusimanual'))->with('error', 'Guru tidak ditemukan.');
        }

        // Siswa yang sudah dibimbing guru lain
        $usedRows = $this->db->table('distribusi_manual')
            ->select('siswa_id')
            ->where('guru_id !=', $guruId)
            ->where('tahun_id', $tahun->id)
            ->get()->getResultArray();

        $usedIds = !empty($usedRows) ? array_column($usedRows, 'siswa_id') : [0];

        // Daftar kelas yang diajar guru ini
        $jamList = $this->db->table('pembagian_jam')
            ->select('pembagian_jam.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id = pembagian_jam.kelas_id')
            ->where('pembagian_jam.guru_id', $guruId)
            ->where('pembagian_jam.tahun_id', $tahun->id)
            ->get()->getResult();

        $totalJatah = 0;
        $totalTerpilih = 0;

        foreach ($jamList as &$j) {
            // Ambil jatah dari pembimbing_pkl
            $pembimbingRow = $this->db->table('pembimbing_pkl')
                ->where('guru_id', $guruId)
                ->where('kelas_id', $j->kelas_id)
                ->where('tahun_id', $tahun->id)
                ->get()->getRow();

            $j->jatah = $pembimbingRow ? (int)$pembimbingRow->jumlah_bimbingan : 0;
            $totalJatah += $j->jatah;

            // Siswa di kelas ini yang belum dibimbing guru lain
            $qSiswa = $this->db->table('siswa')
                ->select('siswa.*, dudi.nama_dudi')
                ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
                ->where('siswa.id_kelas', $j->kelas_id)
                ->where('siswa.id_tahun', $tahun->id);

            if (!empty($usedIds)) {
                $qSiswa->whereNotIn('siswa.id', $usedIds);
            }

            $j->siswa = $qSiswa->orderBy('siswa.nama', 'ASC')->get()->getResult();

            // Siswa yang sudah dipilih oleh guru ini di kelas ini
            $selectedRows = $this->db->table('distribusi_manual')
                ->select('siswa_id')
                ->where('guru_id', $guruId)
                ->where('kelas_id', $j->kelas_id)
                ->where('tahun_id', $tahun->id)
                ->get()->getResultArray();

            $j->selected = !empty($selectedRows) ? array_column($selectedRows, 'siswa_id') : [];
            $totalTerpilih += count($j->selected);
        }

        $data['title']          = 'Mapping Distribusi Siswa - ' . $guru->nama_guru;
        $data['guru']           = $guru;
        $data['jam']            = $jamList;
        $data['tahun']          = $tahun;
        $data['total_jatah']    = $totalJatah;
        $data['total_terpilih'] = $totalTerpilih;

        return template('admin/distribusi_manual/mapping', $data);
    }

    public function simpan()
    {
        $guruId  = (int)($this->request->getPost('guru_id') ?? 0);
        $siswaPost = $this->request->getPost('siswa'); // array: [kelas_id => [siswa_id, ...]]

        $tahun = $this->tahunModel->getAktif();
        if (!$tahun) {
            return redirect()->to(site_url('distribusimanual'))->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }

        $this->db->transStart();

        // Hapus mapping sebelumnya untuk guru ini
        $this->db->table('distribusi_manual')
            ->where('guru_id', $guruId)
            ->where('tahun_id', $tahun->id)
            ->delete();

        if (!empty($siswaPost) && is_array($siswaPost)) {
            foreach ($siswaPost as $kelasId => $siswaList) {
                if (!is_array($siswaList)) continue;

                foreach ($siswaList as $siswaId) {
                    $siswaId = (int)$siswaId;

                    // Pastikan tidak dobel dengan guru lain
                    $cek = $this->db->table('distribusi_manual')
                        ->where('siswa_id', $siswaId)
                        ->where('guru_id !=', $guruId)
                        ->where('tahun_id', $tahun->id)
                        ->get()->getRow();

                    if ($cek) continue;

                    $this->distribusiModel->insert([
                        'guru_id'  => $guruId,
                        'siswa_id' => $siswaId,
                        'kelas_id' => (int)$kelasId,
                        'tahun_id' => $tahun->id
                    ]);

                    // Otomatis sinkronkan pembimbing ke Mapping DUDI
                    $this->db->table('mapping_dudi')
                        ->where('siswa_id', $siswaId)
                        ->where('tahun_id', $tahun->id)
                        ->update(['guru_id' => $guruId]);
                }
            }
        }

        $this->db->transComplete();

        return redirect()->to(site_url('distribusimanual/mapping/' . $guruId))->with('success', 'Mapping siswa berhasil disimpan.');
    }

    public function reset()
    {
        $tahun = $this->tahunModel->getAktif();
        if ($tahun) {
            $this->db->table('distribusi_manual')->where('tahun_id', $tahun->id)->delete();
        }
        return redirect()->to(site_url('distribusimanual'))->with('success', 'Distribusi siswa metode koefisien berhasil direset.');
    }

    public function hapus($id)
    {
        $row = $this->distribusiModel->find($id);
        $guruId = $row ? $row->guru_id : 0;
        if ($row) {
            $this->distribusiModel->delete($id);
        }
        return redirect()->to(site_url('distribusimanual' . ($guruId ? '/mapping/' . $guruId : '')))->with('success', 'Penempatan siswa berhasil dihapus.');
    }
}
