<?php

namespace App\Controllers;

use App\Models\TahunModel;

class Tahun extends BaseController
{
    protected TahunModel $tahunModel;

    public function __construct()
    {
        $this->tahunModel = new TahunModel();
    }

    public function index()
    {
        $data['title'] = 'Tahun Ajaran';
        $data['tahun'] = $this->tahunModel->orderBy('tahun', 'DESC')->findAll();

        return template('admin/tahun/index', $data);
    }

    public function tambah()
    {
        $tahun = trim($this->request->getPost('tahun') ?? '');
        $status = $this->request->getPost('status') ?: 'nonaktif';

        if (empty($tahun)) {
            return redirect()->to(site_url('tahun'))->with('error', 'Nama tahun ajaran wajib diisi.');
        }

        if ($status === 'aktif') {
            $this->tahunModel->builder()->update(['status' => 'nonaktif']);
        }

        $this->tahunModel->insert([
            'tahun'  => $tahun,
            'status' => $status
        ]);

        return redirect()->to(site_url('tahun'))->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function set_aktif($id)
    {
        $this->tahunModel->setAktif($id);
        return redirect()->to(site_url('tahun'))->with('success', 'Tahun ajaran berhasil diaktifkan.');
    }

    public function hapus($id)
    {
        $th = $this->tahunModel->find($id);
        if ($th && $th->status === 'aktif') {
            return redirect()->to(site_url('tahun'))->with('error', 'Tahun ajaran aktif tidak dapat dihapus.');
        }

        $this->tahunModel->delete($id);
        return redirect()->to(site_url('tahun'))->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
