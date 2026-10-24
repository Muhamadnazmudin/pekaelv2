<?php

namespace App\Controllers;

use App\Models\KoefisienModel;
use App\Models\TahunModel;
use App\Models\PengaturanModel;

class Koefisien extends BaseController
{
    protected KoefisienModel $koefisienModel;
    protected TahunModel $tahunModel;
    protected PengaturanModel $pengaturanModel;

    public function __construct()
    {
        $this->koefisienModel  = new KoefisienModel();
        $this->tahunModel      = new TahunModel();
        $this->pengaturanModel = new PengaturanModel();
    }

    public function index()
    {
        $tahun = $this->tahunModel->getAktif();
        $tahunId = $tahun ? $tahun->id : 0;

        $jpDefault = $this->pengaturanModel->getJamPkl();

        $data['title']       = 'Koefisien Pembimbing PKL';
        $data['tahun_aktif'] = $tahun;
        $data['koefisien']   = $this->koefisienModel->getAllWithTahun();
        $data['koef_aktif']  = $this->koefisienModel->getByTahun($tahunId);
        $data['jp_wajib']    = $jpDefault;

        // Preview hitungan saat ini
        if ($tahunId) {
            $data['preview'] = $this->koefisienModel->hitung($tahunId, $jpDefault);
        } else {
            $data['preview'] = null;
        }

        return template('admin/koefisien/index', $data);
    }

    public function hitung()
    {
        $tahun = $this->tahunModel->getAktif();
        if (!$tahun) {
            return redirect()->to(site_url('koefisien'))->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }

        $jp = (int)($this->request->getPost('jp') ?? 0);
        if ($jp <= 0) {
            $jp = $this->pengaturanModel->getJamPkl();
        }

        // Simpan setting JP jika ada perubahan
        $this->pengaturanModel->setJamPkl($jp, $tahun->id);

        $hasil = $this->koefisienModel->hitung($tahun->id, $jp);

        if ($hasil['jumlah_siswa'] <= 0) {
            return redirect()->to(site_url('koefisien'))->with('error', 'Tidak dapat menghitung koefisien: belum ada data siswa PKL di tahun aktif.');
        }

        $cek = $this->koefisienModel->where('tahun_id', $tahun->id)->first();
        $data = [
            'tahun_id'      => $tahun->id,
            'jumlah_siswa'  => $hasil['jumlah_siswa'],
            'jumlah_rombel' => $hasil['jumlah_rombel'],
            'jp'            => $hasil['jp'],
            'koefisien'     => $hasil['koefisien']
        ];

        if ($cek) {
            $this->koefisienModel->update($cek->id, $data);
        } else {
            $this->koefisienModel->insert($data);
        }

        return redirect()->to(site_url('koefisien'))->with('success', 'Koefisien PKL berhasil dihitung dan disimpan: ' . $hasil['koefisien']);
    }
}
