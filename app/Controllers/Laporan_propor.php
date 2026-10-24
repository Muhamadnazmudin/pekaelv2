<?php

namespace App\Controllers;

use App\Models\TahunModel;
use App\Models\SekolahModel;

class Laporan_propor extends BaseController
{
    public function index()
    {
        return $this->pembimbing();
    }

    public function pembimbing()
    {
        $tahunModel = new TahunModel();
        $sekolahModel = new SekolahModel();

        $tahun = $tahunModel->getAktif();
        $tahunId = $tahun ? $tahun->id : 0;

        $guruList = $this->db->table('guru')
            ->where('status', 'aktif')
            ->orderBy('nama_guru', 'ASC')
            ->get()->getResult();

        $laporan = [];

        foreach ($guruList as $g) {
            $kelasGuru = $this->db->table('pembimbing_pkl_perkelas')
                ->select('pembimbing_pkl_perkelas.*, kelas.nama_kelas')
                ->join('kelas', 'kelas.id = pembimbing_pkl_perkelas.kelas_id')
                ->where('pembimbing_pkl_perkelas.guru_id', $g->id)
                ->where('pembimbing_pkl_perkelas.tahun_id', $tahunId)
                ->get()->getResult();

            if (empty($kelasGuru)) continue;

            $kelasData = [];
            $totalJam = 0;
            $totalSiswa = 0;

            foreach ($kelasGuru as $k) {
                $siswaRows = $this->db->table('distribusi_pkl')
                    ->select('siswa.nama, siswa.nisn, dudi.nama_dudi')
                    ->join('siswa', 'siswa.id = distribusi_pkl.siswa_id')
                    ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
                    ->where('distribusi_pkl.guru_id', $g->id)
                    ->where('distribusi_pkl.kelas_id', $k->kelas_id)
                    ->where('distribusi_pkl.tahun_id', $tahunId)
                    ->orderBy('siswa.nama', 'ASC')
                    ->get()->getResult();

                $jumlahSiswa = count($siswaRows);

                $kelasData[] = [
                    'nama_kelas'   => $k->nama_kelas,
                    'jam'          => (float)$k->total_jam,
                    'siswa'        => $jumlahSiswa,
                    'daftar_siswa' => $siswaRows
                ];

                $totalJam += (float)$k->total_jam;
                $totalSiswa += $jumlahSiswa;
            }

            $laporan[] = [
                'guru'        => $g,
                'kelas'       => $kelasData,
                'total_jam'   => $totalJam,
                'total_siswa' => $totalSiswa
            ];
        }

        $data['title']   = 'Laporan Pembimbing (Metode Proporsional Kelas)';
        $data['tahun']   = $tahun;
        $data['sekolah'] = $sekolahModel->getProfil();
        $data['laporan'] = $laporan;

        return template('admin/laporan_propor/pembimbing', $data);
    }

    public function rekap()
    {
        $tahunModel = new TahunModel();
        $sekolahModel = new SekolahModel();

        $tahun = $tahunModel->getAktif();
        $tahunId = $tahun ? $tahun->id : 0;

        $rekap = $this->db->table('distribusi_pkl')
            ->select('distribusi_pkl.id, siswa.nama as nama_siswa, siswa.nisn, kelas.nama_kelas, guru.nama_guru as nama_pembimbing, dudi.nama_dudi, dudi.nomor_mou, dudi.judul_pks')
            ->join('siswa', 'siswa.id = distribusi_pkl.siswa_id')
            ->join('guru', 'guru.id = distribusi_pkl.guru_id')
            ->join('kelas', 'kelas.id = distribusi_pkl.kelas_id')
            ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
            ->where('distribusi_pkl.tahun_id', $tahunId)
            ->orderBy('kelas.nama_kelas', 'ASC')
            ->orderBy('siswa.nama', 'ASC')
            ->get()->getResult();

        $data['title']   = 'Rekap Data PKL (Metode Proporsional Kelas)';
        $data['tahun']   = $tahun;
        $data['sekolah'] = $sekolahModel->getProfil();
        $data['rekap']   = $rekap;

        return template('admin/laporan_propor/rekap', $data);
    }

    public function cetak()
    {
        $tahunModel = new TahunModel();
        $sekolahModel = new SekolahModel();

        $tahun = $tahunModel->getAktif();
        $tahunId = $tahun ? $tahun->id : 0;

        $guruList = $this->db->table('guru')
            ->where('status', 'aktif')
            ->orderBy('nama_guru', 'ASC')
            ->get()->getResult();

        $laporan = [];

        foreach ($guruList as $g) {
            $kelasGuru = $this->db->table('pembimbing_pkl_perkelas')
                ->select('pembimbing_pkl_perkelas.*, kelas.nama_kelas')
                ->join('kelas', 'kelas.id = pembimbing_pkl_perkelas.kelas_id')
                ->where('pembimbing_pkl_perkelas.guru_id', $g->id)
                ->where('pembimbing_pkl_perkelas.tahun_id', $tahunId)
                ->get()->getResult();

            if (empty($kelasGuru)) continue;

            $kelasData = [];
            $totalJam = 0;
            $totalSiswa = 0;

            foreach ($kelasGuru as $k) {
                $siswaRows = $this->db->table('distribusi_pkl')
                    ->select('siswa.nama, siswa.nisn, dudi.nama_dudi')
                    ->join('siswa', 'siswa.id = distribusi_pkl.siswa_id')
                    ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
                    ->where('distribusi_pkl.guru_id', $g->id)
                    ->where('distribusi_pkl.kelas_id', $k->kelas_id)
                    ->where('distribusi_pkl.tahun_id', $tahunId)
                    ->orderBy('siswa.nama', 'ASC')
                    ->get()->getResult();

                $jumlahSiswa = count($siswaRows);

                $kelasData[] = [
                    'nama_kelas'   => $k->nama_kelas,
                    'jam'          => (float)$k->total_jam,
                    'siswa'        => $jumlahSiswa,
                    'daftar_siswa' => $siswaRows
                ];

                $totalJam += (float)$k->total_jam;
                $totalSiswa += $jumlahSiswa;
            }

            $laporan[] = [
                'guru'        => $g,
                'kelas'       => $kelasData,
                'total_jam'   => $totalJam,
                'total_siswa' => $totalSiswa
            ];
        }

        return view('admin/laporan_propor/cetak_pembimbing', [
            'title'   => 'Cetak SK Pembimbing Proporsional Kelas',
            'tahun'   => $tahun,
            'sekolah' => $sekolahModel->getProfil(),
            'laporan' => $laporan
        ]);
    }
}
