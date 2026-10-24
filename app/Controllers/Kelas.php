<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\JurusanModel;

class Kelas extends BaseController
{
    protected KelasModel $kelasModel;
    protected JurusanModel $jurusanModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->jurusanModel = new JurusanModel();
    }

    public function index()
    {
        $data['title'] = 'Data Kelas / Rombel';
        $data['kelas'] = $this->kelasModel->getAllWithJurusan();
        $data['jurusan'] = $this->jurusanModel->getAll();

        return template('admin/kelas/index', $data);
    }

    public function tambah()
    {
        $namaKelas   = trim($this->request->getPost('nama_kelas') ?? '');
        $tingkat     = $this->request->getPost('tingkat') ?: 'XII';
        $statusPkl   = $this->request->getPost('status_pkl') ?: 'ya';
        $jurusanId   = (int)($this->request->getPost('jurusan_id') ?? 0);
        $jumlahSiswa = (int)($this->request->getPost('jumlah_siswa') ?? 0);

        if (empty($namaKelas)) {
            return redirect()->to(site_url('kelas'))->with('error', 'Nama kelas wajib diisi.');
        }

        $this->kelasModel->insert([
            'nama_kelas'   => $namaKelas,
            'tingkat'      => $tingkat,
            'status_pkl'   => $statusPkl,
            'jurusan_id'   => $jurusanId,
            'jumlah_siswa' => $jumlahSiswa
        ]);

        return redirect()->to(site_url('kelas'))->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $namaKelas   = trim($this->request->getPost('nama_kelas') ?? '');
        $tingkat     = $this->request->getPost('tingkat') ?: 'XII';
        $statusPkl   = $this->request->getPost('status_pkl') ?: 'ya';
        $jurusanId   = (int)($this->request->getPost('jurusan_id') ?? 0);
        $jumlahSiswa = (int)($this->request->getPost('jumlah_siswa') ?? 0);

        if (empty($namaKelas)) {
            return redirect()->to(site_url('kelas'))->with('error', 'Nama kelas wajib diisi.');
        }

        $this->kelasModel->update($id, [
            'nama_kelas'   => $namaKelas,
            'tingkat'      => $tingkat,
            'status_pkl'   => $statusPkl,
            'jurusan_id'   => $jurusanId,
            'jumlah_siswa' => $jumlahSiswa
        ]);

        return redirect()->to(site_url('kelas'))->with('success', 'Kelas berhasil diperbarui.');
    }

    public function toggle_pkl($id)
    {
        $k = $this->kelasModel->find($id);
        if ($k) {
            $newStatus = ($k->status_pkl === 'ya') ? 'tidak' : 'ya';
            $this->kelasModel->update($id, ['status_pkl' => $newStatus]);
            return redirect()->to(site_url('kelas'))->with('success', 'Status PKL kelas berhasil diubah menjadi: ' . strtoupper($newStatus));
        }
        return redirect()->to(site_url('kelas'))->with('error', 'Kelas tidak ditemukan.');
    }

    public function hapus($id)
    {
        $this->kelasModel->delete($id);
        return redirect()->to(site_url('kelas'))->with('success', 'Kelas berhasil dihapus.');
    }
}
