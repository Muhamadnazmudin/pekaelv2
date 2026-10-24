<?php

namespace App\Controllers;

use App\Models\DistribusiPklModel;
use App\Models\GuruModel;
use App\Models\TahunModel;

class Distribusi_proporsional extends BaseController
{
    protected DistribusiPklModel $distribusiModel;
    protected GuruModel $guruModel;
    protected TahunModel $tahunModel;

    public function __construct()
    {
        $this->distribusiModel = new DistribusiPklModel();
        $this->guruModel       = new GuruModel();
        $this->tahunModel      = new TahunModel();
    }

    public function index()
    {
        $tahun = $this->tahunModel->getAktif();
        $tahunId = $tahun ? $tahun->id : 0;

        $data['title'] = 'Distribusi Siswa (Metode Proporsional Kelas)';
        $data['tahun'] = $tahun;

        // Guru yang memiliki kuota pembimbing kelas
        $pembimbing = $this->db->table('pembimbing_pkl_perkelas')
            ->select('guru.id as guru_id, guru.nama_guru, guru.nip, SUM(pembimbing_pkl_perkelas.jumlah_bimbingan) as kuota, SUM(pembimbing_pkl_perkelas.total_jam) as total_jam')
            ->join('guru', 'guru.id = pembimbing_pkl_perkelas.guru_id')
            ->where('pembimbing_pkl_perkelas.tahun_id', $tahunId)
            ->groupBy('guru.id, guru.nama_guru, guru.nip')
            ->orderBy('guru.nama_guru', 'ASC')
            ->get()->getResult();

        foreach ($pembimbing as &$p) {
            $p->terbimbing = $this->db->table('distribusi_pkl')
                ->where('guru_id', $p->guru_id)
                ->where('tahun_id', $tahunId)
                ->countAllResults();
        }

        $data['pembimbing'] = $pembimbing;
        $data['distribusi'] = $this->distribusiModel->getAllWithRelations($tahunId);

        return template('admin/distribusi_proporsional/index', $data);
    }

    public function mapping($guruId)
    {
        $tahun = $this->tahunModel->getAktif();
        if (!$tahun) {
            return redirect()->to(site_url('tahun'))->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }

        $guru = $this->guruModel->find($guruId);
        if (!$guru) {
            return redirect()->to(site_url('distribusi_proporsional'))->with('error', 'Guru tidak ditemukan.');
        }

        // Siswa yang sudah dibimbing di metode proporsional
        $usedRows = $this->db->table('distribusi_pkl')
            ->select('siswa_id')
            ->where('tahun_id', $tahun->id)
            ->get()->getResultArray();
        $usedIds = !empty($usedRows) ? array_column($usedRows, 'siswa_id') : [];

        // Jam dan jatah per kelas dari pembimbing_pkl_perkelas
        $jamList = $this->db->table('pembimbing_pkl_perkelas')
            ->select('pembimbing_pkl_perkelas.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id = pembimbing_pkl_perkelas.kelas_id')
            ->where('pembimbing_pkl_perkelas.guru_id', $guruId)
            ->where('pembimbing_pkl_perkelas.tahun_id', $tahun->id)
            ->orderBy('kelas.nama_kelas', 'ASC')
            ->get()->getResult();

        foreach ($jamList as &$j) {
            $j->jatah = (int)$j->jumlah_bimbingan;

            // Siswa yang sudah dipilih oleh guru ini
            $selectedRows = $this->db->table('distribusi_pkl')
                ->select('siswa_id')
                ->where('guru_id', $guruId)
                ->where('kelas_id', $j->kelas_id)
                ->where('tahun_id', $tahun->id)
                ->get()->getResultArray();

            $selectedIds = !empty($selectedRows) ? array_column($selectedRows, 'siswa_id') : [];
            $j->selected = $selectedIds;

            $qSiswa = $this->db->table('siswa')
                ->where('id_kelas', $j->kelas_id)
                ->where('id_tahun', $tahun->id);

            if (!empty($usedIds)) {
                $qSiswa->groupStart();
                    $qSiswa->whereNotIn('id', $usedIds);
                    if (!empty($selectedIds)) {
                        $qSiswa->orWhereIn('id', $selectedIds);
                    }
                $qSiswa->groupEnd();
            }

            $j->siswa = $qSiswa->orderBy('nama', 'ASC')->get()->getResult();
        }

        $data['title'] = 'Mapping Distribusi Proporsional - ' . $guru->nama_guru;
        $data['guru']  = $guru;
        $data['jam']   = $jamList;
        $data['tahun'] = $tahun;

        return template('admin/distribusi_proporsional/mapping', $data);
    }

    public function simpan()
    {
        $guruId    = (int)($this->request->getPost('guru_id') ?? 0);
        $siswaPost = $this->request->getPost('siswa');

        $tahun = $this->tahunModel->getAktif();
        if (!$tahun) {
            return redirect()->to(site_url('distribusi_proporsional'))->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }

        $this->db->transStart();

        $this->db->table('distribusi_pkl')
            ->where('guru_id', $guruId)
            ->where('tahun_id', $tahun->id)
            ->delete();

        if (!empty($siswaPost) && is_array($siswaPost)) {
            foreach ($siswaPost as $kelasId => $siswaList) {
                if (!is_array($siswaList)) continue;

                foreach ($siswaList as $siswaId) {
                    $this->distribusiModel->insert([
                        'guru_id'  => $guruId,
                        'siswa_id' => (int)$siswaId,
                        'kelas_id' => (int)$kelasId,
                        'tahun_id' => $tahun->id,
                        'metode'   => 'otomatis'
                    ]);
                }
            }
        }

        $this->db->transComplete();

        return redirect()->to(site_url('distribusi_proporsional/mapping/' . $guruId))->with('success', 'Mapping distribusi proporsional berhasil disimpan.');
    }

    public function reset()
    {
        $tahun = $this->tahunModel->getAktif();
        if ($tahun) {
            $this->db->table('distribusi_pkl')->where('tahun_id', $tahun->id)->delete();
        }
        return redirect()->to(site_url('distribusi_proporsional'))->with('success', 'Distribusi proporsional berhasil direset.');
    }

    public function hapus($id)
    {
        $row = $this->distribusiModel->find($id);
        $guruId = $row ? $row->guru_id : 0;
        if ($row) {
            $this->distribusiModel->delete($id);
        }
        return redirect()->to(site_url('distribusi_proporsional' . ($guruId ? '/mapping/' . $guruId : '')))->with('success', 'Data berhasil dihapus.');
    }
}
