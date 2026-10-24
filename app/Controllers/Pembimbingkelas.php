<?php

namespace App\Controllers;

use App\Models\PembimbingKelasModel;
use App\Models\KelasModel;
use App\Models\TahunModel;

class Pembimbingkelas extends BaseController
{
    protected PembimbingKelasModel $pembimbingKelasModel;
    protected KelasModel $kelasModel;
    protected TahunModel $tahunModel;

    public function __construct()
    {
        $this->pembimbingKelasModel = new PembimbingKelasModel();
        $this->kelasModel           = new KelasModel();
        $this->tahunModel           = new TahunModel();
    }

    public function index()
    {
        $tahun = $this->tahunModel->getAktif();
        $tahunId = $tahun ? $tahun->id : 0;

        $data['title']      = 'Generate Pembimbing (Metode Proporsional Kelas)';
        $data['tahun']      = $tahun;
        $data['pembimbing'] = $this->pembimbingKelasModel->getAllWithRelations($tahunId);
        $data['kelas']      = $this->kelasModel->getKelasPkl();
        $data['summary']    = $this->pembimbingKelasModel->getSummaryPerGuru($tahunId);

        return template('admin/pembimbingkelas/index', $data);
    }

    public function generate()
    {
        $kelasId = (int)($this->request->getPost('kelas_id') ?? 0);
        if (!$kelasId) {
            return redirect()->to(site_url('pembimbingkelas'))->with('error', 'Kelas belum dipilih.');
        }

        $tahun = $this->tahunModel->getAktif();
        if (!$tahun) {
            return redirect()->to(site_url('pembimbingkelas'))->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }

        $jumlahSiswa = $this->db->table('siswa')
            ->where('id_kelas', $kelasId)
            ->where('id_tahun', $tahun->id)
            ->countAllResults();

        if ($jumlahSiswa <= 0) {
            return redirect()->to(site_url('pembimbingkelas'))->with('error', 'Belum ada siswa pada kelas ini.');
        }

        $guru = $this->db->table('pembagian_jam')
            ->select('guru_id, SUM(jumlah_jam) as total_jam')
            ->where('tahun_id', $tahun->id)
            ->where('kelas_id', $kelasId)
            ->groupBy('guru_id')
            ->orderBy('total_jam', 'DESC')
            ->get()->getResult();

        if (empty($guru)) {
            return redirect()->to(site_url('pembimbingkelas'))->with('error', 'Belum ada pembagian jam pada kelas ini.');
        }

        // Hapus generate lama untuk kelas ini
        $this->db->table('pembimbing_pkl_perkelas')
            ->where('kelas_id', $kelasId)
            ->where('tahun_id', $tahun->id)
            ->delete();

        $dataGenerate = [];
        $totalAwal = 0;

        foreach ($guru as $g) {
            $hasil = ($jumlahSiswa / 46.0) * (float)$g->total_jam;
            $jumlah = (int)floor($hasil);
            $desimal = $hasil - $jumlah;

            $dataGenerate[] = [
                'guru_id'          => (int)$g->guru_id,
                'kelas_id'         => $kelasId,
                'tahun_id'         => $tahun->id,
                'total_jam'        => (float)$g->total_jam,
                'koefisien'        => 46.00,
                'jumlah_bimbingan' => $jumlah,
                'desimal'          => $desimal
            ];

            $totalAwal += $jumlah;
        }

        $sisa = $jumlahSiswa - $totalAwal;

        usort($dataGenerate, function ($a, $b) {
            return $b['desimal'] <=> $a['desimal'];
        });

        $i = 0;
        $count = count($dataGenerate);
        while ($sisa > 0 && $count > 0) {
            if ($i >= $count) {
                $i = 0;
            }
            $dataGenerate[$i]['jumlah_bimbingan']++;
            $sisa--;
            $i++;
        }

        foreach ($dataGenerate as $d) {
            unset($d['desimal']);
            $this->pembimbingKelasModel->insert($d);
        }

        $kRow = $this->kelasModel->find($kelasId);
        $namaKelas = $kRow ? $kRow->nama_kelas : '';

        return redirect()->to(site_url('pembimbingkelas'))->with(
            'success',
            "Generate pembimbing proporsional per kelas {$namaKelas} berhasil!"
        );
    }
}
