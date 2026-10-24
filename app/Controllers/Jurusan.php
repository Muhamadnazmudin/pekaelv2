<?php

namespace App\Controllers;

use App\Models\JurusanModel;
use App\Models\GuruModel;

class Jurusan extends BaseController
{
    protected JurusanModel $jurusanModel;
    protected GuruModel $guruModel;

    public function __construct()
    {
        $this->jurusanModel = new JurusanModel();
        $this->guruModel    = new GuruModel();
    }

    public function index()
    {
        $data['title']    = 'Data Jurusan & Kepala Program Keahlian';
        $data['jurusan']  = $this->jurusanModel->getAll();
        $guruList         = $this->guruModel->getAll();
        $data['guruList'] = $guruList;
        $data['guru']     = $guruList;

        return template('admin/jurusan/index', $data);
    }

    public function tambah()
    {
        $namaJurusan = trim($this->request->getPost('nama_jurusan') ?? '');
        $kodeJurusan = trim($this->request->getPost('kode_jurusan') ?? '');
        $singkatan   = trim($this->request->getPost('singkatan') ?? '');
        $kaprogId    = $this->request->getPost('kepala_program_id') ?: null;

        if (empty($namaJurusan)) {
            return redirect()->to(site_url('jurusan'))->with('error', 'Nama jurusan wajib diisi.');
        }

        $this->jurusanModel->insert([
            'nama_jurusan'      => $namaJurusan,
            'kode_jurusan'      => $kodeJurusan,
            'singkatan'         => $singkatan,
            'kepala_program_id' => $kaprogId ? (int)$kaprogId : null
        ]);

        return redirect()->to(site_url('jurusan'))->with('success', 'Jurusan & Kepala Program berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $namaJurusan = trim($this->request->getPost('nama_jurusan') ?? '');
        $kodeJurusan = trim($this->request->getPost('kode_jurusan') ?? '');
        $singkatan   = trim($this->request->getPost('singkatan') ?? '');
        $kaprogId    = $this->request->getPost('kepala_program_id') ?: null;

        if (empty($namaJurusan)) {
            return redirect()->to(site_url('jurusan'))->with('error', 'Nama jurusan wajib diisi.');
        }

        $this->jurusanModel->update($id, [
            'nama_jurusan'      => $namaJurusan,
            'kode_jurusan'      => $kodeJurusan,
            'singkatan'         => $singkatan,
            'kepala_program_id' => $kaprogId ? (int)$kaprogId : null
        ]);

        return redirect()->to(site_url('jurusan'))->with('success', 'Data jurusan & Kepala Program berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $this->jurusanModel->delete($id);
        return redirect()->to(site_url('jurusan'))->with('success', 'Jurusan berhasil dihapus.');
    }
}
