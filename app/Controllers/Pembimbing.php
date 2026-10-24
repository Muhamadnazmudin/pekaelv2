<?php

namespace App\Controllers;

use App\Models\PembimbingModel;
use App\Models\KelasModel;
use App\Models\TahunModel;
use App\Models\KoefisienModel;

class Pembimbing extends BaseController
{
    protected PembimbingModel $pembimbingModel;
    protected KelasModel $kelasModel;
    protected TahunModel $tahunModel;
    protected KoefisienModel $koefisienModel;

    public function __construct()
    {
        $this->pembimbingModel = new PembimbingModel();
        $this->kelasModel      = new KelasModel();
        $this->tahunModel      = new TahunModel();
        $this->koefisienModel  = new KoefisienModel();
    }

    public function index()
    {
        $tahun = $this->tahunModel->getAktif();
        $tahunId = $tahun ? $tahun->id : 0;

        $data['title']      = 'Generate Pembimbing (Metode Koefisien)';
        $data['tahun']      = $tahun;
        $data['pembimbing'] = $this->pembimbingModel->getAllWithRelations($tahunId);
        $data['kelas']      = $this->kelasModel->getKelasPkl();
        $data['summary']    = $this->pembimbingModel->getSummaryPerGuru($tahunId);

        return template('admin/pembimbing/index', $data);
    }

    public function generate()
    {
        $kelasId = (int)($this->request->getPost('kelas_id') ?? 0);
        if (!$kelasId) {
            return redirect()->to(site_url('pembimbing'))->with('error', 'Kelas belum dipilih.');
        }

        $tahun = $this->tahunModel->getAktif();
        if (!$tahun) {
            return redirect()->to(site_url('pembimbing'))->with('error', 'Tahun ajaran aktif belum ditentukan.');
        }

        $koef = $this->koefisienModel->getByTahun($tahun->id);
        if (!$koef) {
            return redirect()->to(site_url('koefisien'))->with('error', 'Koefisien PKL belum dihitung. Silakan hitung koefisien terlebih dahulu.');
        }

        // Jumlah siswa di kelas ini
        $jumlahSiswa = $this->db->table('siswa')
            ->where('id_kelas', $kelasId)
            ->where('id_tahun', $tahun->id)
            ->countAllResults();

        if ($jumlahSiswa <= 0) {
            return redirect()->to(site_url('pembimbing'))->with('error', 'Belum ada siswa di kelas ini untuk tahun ajaran aktif.');
        }

        // Ambil guru yang mengajar di kelas ini
        $guru = $this->db->table('pembagian_jam')
            ->select('guru_id, SUM(jumlah_jam) as total_jam')
            ->where('tahun_id', $tahun->id)
            ->where('kelas_id', $kelasId)
            ->groupBy('guru_id')
            ->orderBy('total_jam', 'DESC')
            ->get()->getResult();

        if (empty($guru)) {
            return redirect()->to(site_url('pembimbing'))->with('error', 'Belum ada pembagian jam guru pada kelas ini.');
        }

        // Validasi total jam kelas harus sesuai dengan JP wajib koefisien
        $totalJamKelas = 0;
        foreach ($guru as $g) {
            $totalJamKelas += (int)$g->total_jam;
        }

        $jpWajib = (int)$koef->jp;
        if ($totalJamKelas !== $jpWajib) {
            $kRow = $this->kelasModel->find($kelasId);
            $namaKelas = $kRow ? $kRow->nama_kelas : 'Pilihan';
            return redirect()->to(site_url('pembimbing'))->with(
                'error',
                "Kelas {$namaKelas} belum dapat digenerate. Total jam saat ini: {$totalJamKelas} JP, sedangkan wajib {$jpWajib} JP. Silakan lengkapi pembagian jam terlebih dahulu di menu Pembagian Jam."
            );
        }

        // Hapus generate lama untuk kelas ini
        $this->db->table('pembimbing_pkl')
            ->where('kelas_id', $kelasId)
            ->where('tahun_id', $tahun->id)
            ->delete();

        $dataGenerate = [];
        $totalAwal = 0;

        foreach ($guru as $g) {
            $hasil = (float)$g->total_jam / (float)$koef->koefisien;
            $jumlah = (int)round($hasil);
            $desimal = $hasil - floor($hasil);

            // Pembobotan berdasarkan jam mengajar
            $bobot = 0;
            if ($g->total_jam >= 20) {
                $bobot = 3;
            } elseif ($g->total_jam >= 10) {
                $bobot = 2;
            } elseif ($g->total_jam >= 5) {
                $bobot = 1;
            }

            $dataGenerate[] = [
                'guru_id'          => (int)$g->guru_id,
                'kelas_id'         => $kelasId,
                'tahun_id'         => $tahun->id,
                'total_jam'        => (int)$g->total_jam,
                'koefisien'        => (float)$koef->koefisien,
                'jumlah_bimbingan' => $jumlah,
                'desimal'          => $desimal,
                'bobot'            => $bobot
            ];

            $totalAwal += $jumlah;
        }

        // Penanganan selisih siswa
        $sisa = $jumlahSiswa - $totalAwal;

        if ($sisa < 0) {
            // Normalisasi proporsional jika total kuota melebihi siswa kelas
            $totalHasil = 0;
            foreach ($dataGenerate as $item) {
                $totalHasil += $item['jumlah_bimbingan'];
            }

            $totalBaru = 0;
            foreach ($dataGenerate as $k => $item) {
                $ideal = ($totalHasil > 0) ? ($item['jumlah_bimbingan'] / $totalHasil) * $jumlahSiswa : 0;
                $dataGenerate[$k]['jumlah_bimbingan'] = (int)floor($ideal);
                $dataGenerate[$k]['sisa_desimal'] = $ideal - floor($ideal);
                $totalBaru += $dataGenerate[$k]['jumlah_bimbingan'];
            }

            $sisaBagi = $jumlahSiswa - $totalBaru;

            usort($dataGenerate, function ($a, $b) {
                return $b['sisa_desimal'] <=> $a['sisa_desimal'];
            });

            while ($sisaBagi > 0) {
                foreach ($dataGenerate as $k => $item) {
                    $dataGenerate[$k]['jumlah_bimbingan']++;
                    $sisaBagi--;
                    if ($sisaBagi <= 0) break 2;
                }
            }
        }

        // Urutkan berdasarkan bobot tertinggi, jam terbesar, lalu desimal terbesar
        usort($dataGenerate, function ($a, $b) {
            if ($a['bobot'] !== $b['bobot']) {
                return $b['bobot'] <=> $a['bobot'];
            }
            if ($a['total_jam'] !== $b['total_jam']) {
                return $b['total_jam'] <=> $a['total_jam'];
            }
            return $b['desimal'] <=> $a['desimal'];
        });

        // Bagikan sisa kepada guru yang jam >= 5 JP
        while ($sisa > 0) {
            foreach ($dataGenerate as $k => $item) {
                if ($item['total_jam'] < 5) continue;
                $dataGenerate[$k]['jumlah_bimbingan']++;
                $sisa--;
                if ($sisa <= 0) break 2;
            }
        }

        // Simpan ke database
        foreach ($dataGenerate as $d) {
            unset($d['sisa_desimal'], $d['desimal'], $d['bobot']);
            $this->pembimbingModel->insert($d);
        }

        $kRow = $this->kelasModel->find($kelasId);
        $namaKelas = $kRow ? $kRow->nama_kelas : '';

        return redirect()->to(site_url('pembimbing'))->with(
            'success',
            "Generate pembimbing kelas {$namaKelas} berhasil! Kuota bimbingan telah dihitung presisi untuk Dapodik."
        );
    }
}
