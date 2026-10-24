<?php

namespace App\Controllers;

use App\Models\SekolahModel;

class Cek extends BaseController
{
    public function index()
    {
        $sekolahModel = new SekolahModel();
        return view('siswa/cek', [
            'title'   => 'Cek Status Penempatan PKL Siswa',
            'sekolah' => $sekolahModel->getProfil()
        ]);
    }

    public function hasil()
    {
        $nisn = trim($this->request->getPost('nisn') ?? '');
        if (empty($nisn)) {
            return redirect()->to(site_url('cek'))->with('error', 'Silakan masukkan NISN Anda.');
        }

        $sekolahModel = new SekolahModel();

        // Cari siswa beserta kelas, dudi, dan pembimbing
        $siswa = $this->db->table('siswa')
            ->select('siswa.*, kelas.nama_kelas, kelas.tingkat, dudi.nama_dudi, dudi.alamat as alamat_dudi, dudi.nama_pic, dudi.no_hp_pic, guru.nama_guru as nama_pembimbing, guru.nip as nip_pembimbing')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
            ->join('distribusi_manual', 'distribusi_manual.siswa_id = siswa.id', 'left')
            ->join('guru', 'guru.id = distribusi_manual.guru_id', 'left')
            ->where('siswa.nisn', $nisn)
            ->get()->getRow();

        if (!$siswa) {
            return redirect()->to(site_url('cek'))->with('error', "Siswa dengan NISN: {$nisn} tidak ditemukan.");
        }

        // Catat log_cek
        $this->db->table('log_cek')->insert([
            'nisn'       => $siswa->nisn,
            'nama'       => $siswa->nama,
            'waktu'      => date('Y-m-d H:i:s'),
            'ip_address' => $this->request->getIPAddress()
        ]);

        return view('siswa/hasil', [
            'title'   => 'Hasil Penempatan PKL - ' . $siswa->nama,
            'siswa'   => $siswa,
            'sekolah' => $sekolahModel->getProfil()
        ]);
    }
}
