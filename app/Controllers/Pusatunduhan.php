<?php

namespace App\Controllers;

use App\Models\PusatUnduhanModel;

class Pusatunduhan extends BaseController
{
    protected PusatUnduhanModel $unduhanModel;

    public function __construct()
    {
        $this->unduhanModel = new PusatUnduhanModel();
    }

    public function index()
    {
        $data['title'] = 'Pusat Unduhan & Format Berkas';
        $data['unduhan'] = $this->unduhanModel->orderBy('id', 'DESC')->findAll();

        return template('admin/pusat_unduhan/index', $data);
    }

    public function tambah()
    {
        $judul    = trim($this->request->getPost('judul') ?? '');
        $kategori = trim($this->request->getPost('kategori') ?? 'Template');
        $file     = $this->request->getFile('file');

        if (empty($judul) || !$file || !$file->isValid()) {
            return redirect()->to(site_url('pusatunduhan'))->with('error', 'Judul dan berkas file wajib diisi.');
        }

        $newName = $file->getRandomName();
        $file->move(ROOTPATH . 'uploads/unduhan', $newName);
        $size = round($file->getSize() / 1024, 1) . ' KB';

        $this->unduhanModel->insert([
            'judul'       => $judul,
            'kategori'    => $kategori,
            'file'        => $newName,
            'ukuran_file' => $size
        ]);

        return redirect()->to(site_url('pusatunduhan'))->with('success', 'File berhasil diunggah.');
    }

    public function hapus($id)
    {
        $row = $this->unduhanModel->find($id);
        if ($row && !empty($row->file)) {
            $filePath = ROOTPATH . 'uploads/unduhan/' . $row->file;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        $this->unduhanModel->delete($id);

        return redirect()->to(site_url('pusatunduhan'))->with('success', 'File berhasil dihapus.');
    }
}
