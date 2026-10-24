<?php

namespace App\Controllers;

use App\Models\TahunModel;
use App\Models\SekolahModel;

class LaporanPembimbing extends BaseController
{
    public function index()
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
            $kelasGuru = $this->db->table('pembagian_jam')
                ->select('pembagian_jam.*, kelas.nama_kelas')
                ->join('kelas', 'kelas.id = pembagian_jam.kelas_id')
                ->where('pembagian_jam.guru_id', $g->id)
                ->where('pembagian_jam.tahun_id', $tahunId)
                ->where('pembagian_jam.jumlah_jam >', 0)
                ->get()->getResult();

            if (empty($kelasGuru)) continue;

            $kelasData = [];
            $totalJam = 0;
            $totalSiswa = 0;

            foreach ($kelasGuru as $k) {
                $siswaRows = $this->db->table('distribusi_manual')
                    ->select('siswa.nama, siswa.nisn, dudi.nama_dudi')
                    ->join('siswa', 'siswa.id = distribusi_manual.siswa_id')
                    ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
                    ->where('distribusi_manual.guru_id', $g->id)
                    ->where('distribusi_manual.kelas_id', $k->kelas_id)
                    ->where('distribusi_manual.tahun_id', $tahunId)
                    ->orderBy('siswa.nama', 'ASC')
                    ->get()->getResult();

                $jumlahSiswa = count($siswaRows);

                $kelasData[] = [
                    'nama_kelas'   => $k->nama_kelas,
                    'jam'          => (int)$k->jumlah_jam,
                    'siswa'        => $jumlahSiswa,
                    'daftar_siswa' => $siswaRows
                ];

                $totalJam += (int)$k->jumlah_jam;
                $totalSiswa += $jumlahSiswa;
            }

            $laporan[] = [
                'guru'        => $g,
                'kelas'       => $kelasData,
                'total_jam'   => $totalJam,
                'total_siswa' => $totalSiswa
            ];
        }

        $data['title']   = 'Laporan Pembagian Pembimbing (Metode Koefisien)';
        $data['tahun']   = $tahun;
        $data['sekolah'] = $sekolahModel->getProfil();
        $data['laporan'] = $laporan;

        return template('admin/laporan/pembimbing', $data);
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
            $kelasGuru = $this->db->table('pembagian_jam')
                ->select('pembagian_jam.*, kelas.nama_kelas')
                ->join('kelas', 'kelas.id = pembagian_jam.kelas_id')
                ->where('pembagian_jam.guru_id', $g->id)
                ->where('pembagian_jam.tahun_id', $tahunId)
                ->where('pembagian_jam.jumlah_jam >', 0)
                ->get()->getResult();

            if (empty($kelasGuru)) continue;

            $kelasData = [];
            $totalJam = 0;
            $totalSiswa = 0;

            foreach ($kelasGuru as $k) {
                $siswaRows = $this->db->table('distribusi_manual')
                    ->select('siswa.nama, siswa.nisn, dudi.nama_dudi')
                    ->join('siswa', 'siswa.id = distribusi_manual.siswa_id')
                    ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
                    ->where('distribusi_manual.guru_id', $g->id)
                    ->where('distribusi_manual.kelas_id', $k->kelas_id)
                    ->where('distribusi_manual.tahun_id', $tahunId)
                    ->orderBy('siswa.nama', 'ASC')
                    ->get()->getResult();

                $jumlahSiswa = count($siswaRows);

                $kelasData[] = [
                    'nama_kelas'   => $k->nama_kelas,
                    'jam'          => (int)$k->jumlah_jam,
                    'siswa'        => $jumlahSiswa,
                    'daftar_siswa' => $siswaRows
                ];

                $totalJam += (int)$k->jumlah_jam;
                $totalSiswa += $jumlahSiswa;
            }

            $laporan[] = [
                'guru'        => $g,
                'kelas'       => $kelasData,
                'total_jam'   => $totalJam,
                'total_siswa' => $totalSiswa
            ];
        }

        return view('admin/laporan/cetak_pembimbing', [
            'title'   => 'Cetak SK & Laporan JJM Pembimbing PKL',
            'tahun'   => $tahun,
            'sekolah' => $sekolahModel->getProfil(),
            'laporan' => $laporan
        ]);
    }
}
