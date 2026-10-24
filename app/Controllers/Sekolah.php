<?php

namespace App\Controllers;

use App\Models\SekolahModel;

class Sekolah extends BaseController
{
    protected SekolahModel $sekolahModel;

    public function __construct()
    {
        $this->sekolahModel = new SekolahModel();
    }

    public function index()
    {
        $data['title'] = 'Profil Sekolah';
        $data['sekolah'] = $this->sekolahModel->getProfil();

        return template('admin/sekolah/index', $data);
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $profil = $this->sekolahModel->find($id);

        if (!$profil) {
            return redirect()->to(site_url('sekolah'))->with('error', 'Data profil sekolah tidak ditemukan.');
        }

        $namaSekolah   = trim($this->request->getPost('nama_sekolah') ?? '');
        $npsn          = trim($this->request->getPost('npsn') ?? '');
        $alamat        = trim($this->request->getPost('alamat') ?? '');
        $kepalaSekolah = trim($this->request->getPost('kepala_sekolah') ?? '');
        $nipKepsek     = trim($this->request->getPost('nip_kepsek') ?? '');

        $dataUpdate = [
            'nama_sekolah'   => $namaSekolah,
            'npsn'           => $npsn,
            'alamat'         => $alamat,
            'kepala_sekolah' => $kepalaSekolah,
            'nip_kepsek'     => $nipKepsek,
        ];

        // Upload Logo jika ada
        $fileLogo = $this->request->getFile('logo');
        if ($fileLogo && $fileLogo->isValid() && !$fileLogo->hasMoved()) {
            $newName = $fileLogo->getRandomName();
            $fileLogo->move(ROOTPATH . 'uploads/logo', $newName);
            $dataUpdate['logo'] = $newName;
        }

        $this->sekolahModel->update($id, $dataUpdate);

        return redirect()->to(site_url('sekolah'))->with('success', 'Profil sekolah berhasil diperbarui.');
    }
}
