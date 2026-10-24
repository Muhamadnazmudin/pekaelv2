<?php

namespace App\Controllers;

use App\Models\PembagianJamModel;
use App\Models\GuruModel;
use App\Models\KelasModel;
use App\Models\TahunModel;
use App\Models\PengaturanModel;

class Pembagianjam extends BaseController
{
    protected PembagianJamModel $jamModel;
    protected GuruModel $guruModel;
    protected KelasModel $kelasModel;
    protected TahunModel $tahunModel;
    protected PengaturanModel $pengaturanModel;

    public function __construct()
    {
        $this->jamModel        = new PembagianJamModel();
        $this->guruModel       = new GuruModel();
        $this->kelasModel      = new KelasModel();
        $this->tahunModel      = new TahunModel();
        $this->pengaturanModel = new PengaturanModel();
    }

    public function index()
    {
        $tahun = $this->tahunModel->getAktif();
        $tahunId = $tahun ? $tahun->id : 0;

        $jpWajib = $this->pengaturanModel->getJamPkl();

        $data['title']          = 'Pembagian Jam Mengajar PKL';
        $data['tahun']          = $tahun;
        $data['jp_wajib']       = $jpWajib;
        $data['jam']            = $this->jamModel->getAllWithRelations($tahunId);
        $data['guru']           = $this->guruModel->getAktif();
        $data['kelas']          = $this->kelasModel->getKelasPkl();
        $data['recap_kelas']    = $this->jamModel->getRecapPerKelas($tahunId);

        return template('admin/pembagian_jam/index', $data);
    }

    public function simpan_ajax()
    {
        $guruId    = (int)($this->request->getPost('guru_id') ?? 0);
        $kelasId   = (int)($this->request->getPost('kelas_id') ?? 0);
        $jumlahJam = (int)($this->request->getPost('jumlah_jam') ?? 0);

        if (!$guruId || !$kelasId || $jumlahJam <= 0) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data guru, kelas, dan jumlah jam wajib diisi dengan benar.'
            ]);
        }

        $tahun = $this->tahunModel->getAktif();
        if (!$tahun) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Tahun ajaran aktif belum ditentukan.'
            ]);
        }

        // Cek apakah sudah ada entri guru dan kelas ini
        $cek = $this->jamModel->where('guru_id', $guruId)
                              ->where('kelas_id', $kelasId)
                              ->where('tahun_id', $tahun->id)
                              ->first();

        if ($cek) {
            $this->jamModel->update($cek->id, ['jumlah_jam' => $jumlahJam]);
        } else {
            $this->jamModel->insert([
                'guru_id'    => $guruId,
                'kelas_id'   => $kelasId,
                'tahun_id'   => $tahun->id,
                'jumlah_jam' => $jumlahJam
            ]);
        }

        // Hitung total jam kelas saat ini
        $totalJamKelas = $this->db->table('pembagian_jam')
            ->selectSum('jumlah_jam')
            ->where('kelas_id', $kelasId)
            ->where('tahun_id', $tahun->id)
            ->get()->getRow()->jumlah_jam ?? 0;

        return $this->response->setJSON([
            'status'          => true,
            'message'         => 'Pembagian jam berhasil disimpan.',
            'total_jam_kelas' => (int)$totalJamKelas
        ]);
    }

    public function tambah()
    {
        $tahun = $this->tahunModel->getAktif();
        if (!$tahun) {
            return redirect()->to(site_url('pembagianjam'))->with('error', 'Tahun ajaran aktif belum ada.');
        }

        $guruId    = (int)($this->request->getPost('guru_id') ?? 0);
        $kelasId   = (int)($this->request->getPost('kelas_id') ?? 0);
        $jumlahJam = (int)($this->request->getPost('jumlah_jam') ?? 0);

        if (!$guruId || !$kelasId || $jumlahJam <= 0) {
            return redirect()->to(site_url('pembagianjam'))->with('error', 'Semua data pembagian jam wajib diisi.');
        }

        $cek = $this->jamModel->where('guru_id', $guruId)
                              ->where('kelas_id', $kelasId)
                              ->where('tahun_id', $tahun->id)
                              ->first();

        if ($cek) {
            $this->jamModel->update($cek->id, ['jumlah_jam' => $jumlahJam]);
        } else {
            $this->jamModel->insert([
                'guru_id'    => $guruId,
                'kelas_id'   => $kelasId,
                'tahun_id'   => $tahun->id,
                'jumlah_jam' => $jumlahJam
            ]);
        }

        return redirect()->to(site_url('pembagianjam'))->with('success', 'Pembagian jam berhasil disimpan.');
    }

    public function hapus($id)
    {
        $this->jamModel->delete($id);
        return redirect()->to(site_url('pembagianjam'))->with('success', 'Data alokasi jam berhasil dihapus.');
    }

    /**
     * AJAX: Ambil alokasi jam guru untuk 1 rombel tertentu
     */
    public function get_kelas_allocation($kelasId = null)
    {
        $kelasId = (int)($kelasId ?: $this->request->getGet('kelas_id') ?: 0);
        if (!$kelasId) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'ID kelas tidak valid.'
            ]);
        }

        $tahun = $this->tahunModel->getAktif();
        $tahunId = $tahun ? $tahun->id : 0;

        $kelas = $this->kelasModel->find($kelasId);
        if (!$kelas) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Kelas tidak ditemukan.'
            ]);
        }

        $records = $this->db->table('pembagian_jam')
            ->select('pembagian_jam.id, pembagian_jam.guru_id, pembagian_jam.jumlah_jam, guru.nama_guru, guru.nip')
            ->join('guru', 'guru.id = pembagian_jam.guru_id', 'left')
            ->where('pembagian_jam.kelas_id', $kelasId)
            ->where('pembagian_jam.tahun_id', $tahunId)
            ->orderBy('pembagian_jam.jumlah_jam', 'DESC')
            ->get()->getResult();

        $allocations = [];
        $totalJam = 0;
        foreach ($records as $r) {
            $jam = (int)$r->jumlah_jam;
            $totalJam += $jam;
            $allocations[] = [
                'id'         => $r->id,
                'guru_id'    => (int)$r->guru_id,
                'nama_guru'  => $r->nama_guru,
                'nip'        => $r->nip,
                'jumlah_jam' => $jam,
                'total_jam'  => $jam
            ];
        }

        $targetJp = $this->pengaturanModel->getJamPkl();

        return $this->response->setJSON([
            'status'      => true,
            'kelas'       => [
                'id'         => $kelas->id,
                'nama_kelas' => $kelas->nama_kelas,
                'tingkat'    => $kelas->tingkat
            ],
            'allocations' => $allocations,
            'total_jam'   => $totalJam,
            'target_jp'   => $targetJp
        ]);
    }

    /**
     * AJAX: Simpan alokasi beberapa guru sekaligus untuk 1 rombel (Mapping Cepat Modal)
     */
    public function simpan_bulk()
    {
        $kelasId   = (int)($this->request->getPost('kelas_id') ?? 0);
        $guruIds   = $this->request->getPost('guru_id') ?? [];
        $jumlahJam = $this->request->getPost('jumlah_jam') ?? [];

        if (!$kelasId) {
            return $this->response->setJSON([
                'status'     => false,
                'message'    => 'Kelas tidak valid.',
                'csrf_token' => csrf_hash()
            ]);
        }

        $tahun = $this->tahunModel->getAktif();
        if (!$tahun) {
            return $this->response->setJSON([
                'status'     => false,
                'message'    => 'Tahun ajaran aktif belum ditentukan.',
                'csrf_token' => csrf_hash()
            ]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Hapus alokasi lama untuk kelas ini pada tahun aktif
        $db->table('pembagian_jam')
            ->where('kelas_id', $kelasId)
            ->where('tahun_id', $tahun->id)
            ->delete();

        $totalJam = 0;
        $insertedGurus = [];

        if (is_array($guruIds) && is_array($jumlahJam)) {
            foreach ($guruIds as $idx => $gid) {
                $gId = (int)$gid;
                $jam = (int)($jumlahJam[$idx] ?? 0);

                if ($gId > 0 && $jam > 0) {
                    // Jika guru yang sama dimasukkan lebih dari sekali, akumulasikan jamnya
                    if (isset($insertedGurus[$gId])) {
                        $insertedGurus[$gId] += $jam;
                    } else {
                        $insertedGurus[$gId] = $jam;
                    }
                }
            }

            foreach ($insertedGurus as $gId => $jam) {
                $db->table('pembagian_jam')->insert([
                    'guru_id'    => $gId,
                    'kelas_id'   => $kelasId,
                    'tahun_id'   => $tahun->id,
                    'jumlah_jam' => $jam
                ]);
                $totalJam += $jam;
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status'     => false,
                'message'    => 'Gagal menyimpan pembagian jam.',
                'csrf_token' => csrf_hash()
            ]);
        }

        $targetJp = $this->pengaturanModel->getJamPkl();
        $isPas    = ($totalJam === (int)$targetJp);

        $kelas = $this->kelasModel->find($kelasId);
        $namaKelas = $kelas ? $kelas->nama_kelas : 'Kelas';

        $msg = "Alokasi {$namaKelas} berhasil disimpan (Total: {$totalJam} / {$targetJp} JP).";
        if ($isPas) {
            $msg = "Mantap! Alokasi {$namaKelas} pas {$targetJp} JP.";
        }

        return $this->response->setJSON([
            'status'          => true,
            'message'         => $msg,
            'is_pas'          => $isPas,
            'total_jam_kelas' => $totalJam,
            'csrf_token'      => csrf_hash()
        ]);
    }
}

