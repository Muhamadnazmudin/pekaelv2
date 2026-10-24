<?php

namespace App\Controllers;

use App\Models\DapodikModel;
use App\Models\TahunModel;

class Dapodik extends BaseController
{
    protected DapodikModel $dapodikModel;

    public function __construct()
    {
        $this->dapodikModel = new DapodikModel();
    }

    public function index()
    {
        $tahunModel = new TahunModel();
        $tahun = $tahunModel->getAktif();

        $dapoStatus = $this->dapodikModel->testConnection();

        // Data saat ini di PEKAEL
        $pekaelStats = [
            'total_jurusan' => $this->db->table('jurusan')->countAllResults(),
            'total_kelas'   => $this->db->table('kelas')->countAllResults(),
            'total_guru'    => $this->db->table('guru')->countAllResults(),
            'total_siswa'   => $this->db->table('siswa')->where('id_tahun', $tahun->id ?? 0)->countAllResults(),
            'total_dudi'    => $this->db->table('dudi')->countAllResults()
        ];

        return template('admin/dapodik/index', [
            'title'        => 'Integrasi Dapodik (Read-Only)',
            'tahun'        => $tahun,
            'dapo_status'  => $dapoStatus,
            'pekael_stats' => $pekaelStats
        ]);
    }

    public function test()
    {
        $res = $this->dapodikModel->testConnection();
        return $this->response->setJSON($res);
    }

    public function tarik_sekolah()
    {
        $res = $this->dapodikModel->tarikSekolah();
        if ($res['success']) {
            return redirect()->back()->with('success', $res['message']);
        }
        return redirect()->back()->with('error', $res['message']);
    }

    public function tarik_jurusan()
    {
        $res = $this->dapodikModel->tarikJurusan();
        if ($res['success']) {
            return redirect()->back()->with('success', $res['message']);
        }
        return redirect()->back()->with('error', $res['message']);
    }

    public function tarik_kelas()
    {
        $res = $this->dapodikModel->tarikKelas();
        if ($res['success']) {
            return redirect()->back()->with('success', $res['message']);
        }
        return redirect()->back()->with('error', $res['message']);
    }

    public function tarik_siswa()
    {
        $tahunModel = new TahunModel();
        $tahun = $tahunModel->getAktif();
        if (!$tahun) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }

        $res = $this->dapodikModel->tarikSiswa($tahun->id, true);
        if ($res['success']) {
            return redirect()->back()->with('success', $res['message']);
        }
        return redirect()->back()->with('error', $res['message']);
    }

    public function tarik_dudi()
    {
        $res = $this->dapodikModel->tarikDudi();
        if ($res['success']) {
            return redirect()->back()->with('success', $res['message']);
        }
        return redirect()->back()->with('error', $res['message']);
    }

    public function tarik_guru()
    {
        $res = $this->dapodikModel->tarikGuru();
        if ($res['success']) {
            return redirect()->back()->with('success', $res['message']);
        }
        return redirect()->back()->with('error', $res['message']);
    }

    public function tarik_semua()
    {
        $tahunModel = new TahunModel();
        $tahun = $tahunModel->getAktif();
        if (!$tahun) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }

        // Eksekusi urutan teratur
        $this->dapodikModel->tarikSekolah();
        $rJurusan = $this->dapodikModel->tarikJurusan();
        $rKelas   = $this->dapodikModel->tarikKelas();
        $rDudi    = $this->dapodikModel->tarikDudi();
        $rGuru    = $this->dapodikModel->tarikGuru();
        $rSiswa   = $this->dapodikModel->tarikSiswa($tahun->id, true);

        $msg = sprintf(
            'Penarikan seluruh data dari Dapodik berhasil! Jurusan (+%d), Kelas (+%d), DUDI (+%d), Guru (+%d), Siswa (+%d).',
            $rJurusan['inserted'] ?? 0,
            $rKelas['inserted'] ?? 0,
            $rDudi['inserted'] ?? 0,
            $rGuru['inserted'] ?? 0,
            $rSiswa['inserted'] ?? 0
        );

        return redirect()->to(site_url('dapodik'))->with('success', $msg);
    }
}
