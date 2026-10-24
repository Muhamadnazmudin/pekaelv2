<?php

namespace App\Controllers;

use App\Models\SekolahModel;

class PortalGuru extends BaseController
{
    protected function getGuruId()
    {
        return session()->get('guru_id');
    }

    protected function getSiswaBimbinganList($guruId)
    {
        // Ambil siswa bimbingan dari distribusi_manual (Koefisien) atau distribusi_pkl (Proporsional) atau mapping_dudi
        return $this->db->table('siswa')
            ->select('siswa.*, MAX(kelas.nama_kelas) as nama_kelas, MAX(jurusan.nama_jurusan) as nama_jurusan, MAX(dudi.nama_dudi) as nama_dudi, MAX(dudi.alamat) as alamat_dudi, MAX(dudi.nama_pic) as nama_pic, MAX(dudi.no_hp_pic) as no_hp_pic,
                      COALESCE(MAX(absen.status), "belum") as status_absen_hari_ini, MAX(absen.jam_masuk) as jam_masuk, MAX(absen.jam_pulang) as jam_pulang, MAX(absen.foto_masuk) as foto_masuk, MAX(absen.jarak_masuk) as jarak_masuk')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
            ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
            ->join('distribusi_manual dm', 'dm.siswa_id = siswa.id', 'left')
            ->join('distribusi_pkl dp', 'dp.siswa_id = siswa.id', 'left')
            ->join('mapping_dudi md', 'md.siswa_id = siswa.id', 'left')
            ->join('absensi_pkl absen', 'absen.siswa_id = siswa.id AND absen.tanggal = "' . date('Y-m-d') . '"', 'left')
            ->groupStart()
                ->where('dm.guru_id', $guruId)
                ->orWhere('dp.guru_id', $guruId)
                ->orWhere('md.guru_id', $guruId)
            ->groupEnd()
            ->groupBy('siswa.id')
            ->orderBy('MAX(kelas.nama_kelas)', 'ASC')
            ->orderBy('siswa.nama', 'ASC')
            ->get()->getResult();
    }

    public function dashboard()
    {
        $guruId = $this->getGuruId();
        if (!$guruId) {
            return redirect()->to(site_url('login'));
        }

        $guru = $this->db->table('guru')->where('id', $guruId)->get()->getRow();
        $isPembimbing = session()->get('is_pembimbing');
        $isKaprog     = session()->get('is_kaprog');

        $siswaBimbingan = [];
        $totalBimbingan = 0;
        $hadirHariIni   = 0;
        $jurnalMenunggu = 0;

        if ($isPembimbing) {
            $siswaBimbingan = $this->getSiswaBimbinganList($guruId);
            $totalBimbingan = count($siswaBimbingan);

            foreach ($siswaBimbingan as $s) {
                if ($s->status_absen_hari_ini === 'hadir') {
                    $hadirHariIni++;
                }
            }

            // Hitung jurnal siswa bimbingan yang menunggu verifikasi
            $siswaIds = array_column($siswaBimbingan, 'id');
            if (!empty($siswaIds)) {
                $jurnalMenunggu = $this->db->table('jurnal_pkl')
                    ->whereIn('siswa_id', $siswaIds)
                    ->where('status_verifikasi', 'menunggu')
                    ->countAllResults();
            }
        }

        // Ambil data jurusan yang diampu jika Kaprog secara real-time dari database
        $kaprogJurusans = $this->db->table('jurusan')
            ->where('kepala_program_id', $guruId)
            ->orderBy('nama_jurusan', 'ASC')
            ->get()->getResult();

        $isKaprog = !empty($kaprogJurusans);
        session()->set('is_kaprog', $isKaprog);
        session()->set('kaprog_jurusans', array_column($kaprogJurusans, 'id'));

        return view('portal_guru/dashboard', [
            'title'          => 'Dashboard Guru Pembimbing PKL',
            'guru'           => $guru,
            'isPembimbing'   => $isPembimbing,
            'isKaprog'       => $isKaprog,
            'kaprogJurusans' => $kaprogJurusans,
            'siswaBimbingan' => $siswaBimbingan,
            'totalBimbingan' => $totalBimbingan,
            'hadirHariIni'   => $hadirHariIni,
            'jurnalMenunggu' => $jurnalMenunggu,
            'sekolah'        => (new SekolahModel())->getProfil()
        ]);
    }

    public function siswa()
    {
        $guruId = $this->getGuruId();
        if (!$guruId) return redirect()->to(site_url('login'));

        $isPembimbing = session()->get('is_pembimbing');
        if (!$isPembimbing) {
            return redirect()->to(site_url('guru/dashboard'))->with('error', 'Anda belum ditugaskan sebagai pembimbing PKL.');
        }

        $siswaBimbingan = $this->getSiswaBimbinganList($guruId);

        return view('portal_guru/siswa', [
            'title'          => 'Daftar Siswa Bimbingan PKL',
            'siswaBimbingan' => $siswaBimbingan
        ]);
    }

    public function absensi()
    {
        $guruId = $this->getGuruId();
        if (!$guruId) return redirect()->to(site_url('login'));

        $siswaBimbingan = $this->getSiswaBimbinganList($guruId);
        $siswaIds = array_column($siswaBimbingan, 'id');

        $tanggal = $this->request->getGet('tanggal') ?: date('Y-m-d');

        $absensi = [];
        if (!empty($siswaIds)) {
            $absensi = $this->db->table('absensi_pkl')
                ->select('absensi_pkl.*, siswa.nama as nama_siswa, siswa.nisn, kelas.nama_kelas, dudi.nama_dudi, dudi.radius_meter')
                ->join('siswa', 'siswa.id = absensi_pkl.siswa_id')
                ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
                ->join('dudi', 'dudi.id = absensi_pkl.dudi_id', 'left')
                ->whereIn('absensi_pkl.siswa_id', $siswaIds)
                ->where('absensi_pkl.tanggal', $tanggal)
                ->orderBy('absensi_pkl.jam_masuk', 'DESC')
                ->get()->getResult();
        }

        return view('portal_guru/absensi', [
            'title'          => 'Monitoring Absensi Siswa Bimbingan',
            'absensi'        => $absensi,
            'tanggal'        => $tanggal,
            'totalBimbingan' => count($siswaBimbingan)
        ]);
    }

    public function jurnal()
    {
        $guruId = $this->getGuruId();
        if (!$guruId) return redirect()->to(site_url('login'));

        $siswaBimbingan = $this->getSiswaBimbinganList($guruId);
        $siswaIds = array_column($siswaBimbingan, 'id');

        $statusFilter = $this->request->getGet('status') ?: '';

        $jurnals = [];
        if (!empty($siswaIds)) {
            $builder = $this->db->table('jurnal_pkl')
                ->select('jurnal_pkl.*, siswa.nama as nama_siswa, siswa.nisn, kelas.nama_kelas, dudi.nama_dudi')
                ->join('siswa', 'siswa.id = jurnal_pkl.siswa_id')
                ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
                ->join('dudi', 'dudi.id = jurnal_pkl.dudi_id', 'left')
                ->whereIn('jurnal_pkl.siswa_id', $siswaIds);

            if (!empty($statusFilter)) {
                $builder->where('jurnal_pkl.status_verifikasi', $statusFilter);
            }

            $jurnals = $builder->orderBy('jurnal_pkl.tanggal', 'DESC')
                               ->orderBy('jurnal_pkl.id', 'DESC')
                               ->get()->getResult();
        }

        return view('portal_guru/jurnal', [
            'title'        => 'Monitoring & Validasi Jurnal PKL',
            'jurnals'      => $jurnals,
            'statusFilter' => $statusFilter
        ]);
    }

    public function jurnal_verifikasi($id)
    {
        $guruId = $this->getGuruId();
        if (!$guruId) return redirect()->to(site_url('login'));

        $status  = $this->request->getPost('status_verifikasi') ?: 'disetujui';
        $catatan = trim($this->request->getPost('catatan_pembimbing') ?? '');

        $this->db->table('jurnal_pkl')
            ->where('id', $id)
            ->update([
                'status_verifikasi'  => $status,
                'catatan_pembimbing' => $catatan ?: null,
                'guru_id'            => $guruId,
                'waktu_verifikasi'   => date('Y-m-d H:i:s')
            ]);

        $statusLabel = ($status === 'disetujui') ? 'Disetujui' : 'Diminta Revisi';
        return redirect()->back()->with('success', "Jurnal berhasil diverifikasi dengan status: {$statusLabel}.");
    }

    public function kaprog()
    {
        $guruId = $this->getGuruId();
        if (!$guruId) {
            return redirect()->to(site_url('login'));
        }

        // Ambil jurusan binaan Kaprog secara real-time dari database
        $jurusans = $this->db->table('jurusan')
            ->where('kepala_program_id', $guruId)
            ->orderBy('nama_jurusan', 'ASC')
            ->get()->getResult();

        if (empty($jurusans)) {
            return redirect()->to(site_url('guru/dashboard'))->with('error', 'Anda belum ditugaskan sebagai Kepala Program Keahlian pada jurusan apapun.');
        }

        $kaprogJurusanIds = array_column($jurusans, 'id');
        session()->set('is_kaprog', true);
        session()->set('kaprog_jurusans', $kaprogJurusanIds);

        // Ambil kelas yang ada pada jurusan-jurusan tersebut
        $kelas = $this->db->table('kelas')
            ->select('kelas.*, jurusan.nama_jurusan')
            ->join('jurusan', 'jurusan.id = kelas.jurusan_id')
            ->whereIn('kelas.jurusan_id', $kaprogJurusanIds)
            ->orderBy('kelas.nama_kelas', 'ASC')
            ->get()->getResult();

        // Hitung rombel per jurusan
        foreach ($jurusans as $j) {
            $countKelas = 0;
            foreach ($kelas as $k) {
                if ((int)$k->jurusan_id === (int)$j->id) {
                    $countKelas++;
                }
            }
            $j->total_kelas = $countKelas;
        }

        $jurusanPilih = $this->request->getGet('jurusan_id') ?: null;
        $kelasPilih   = $this->request->getGet('kelas_id') ?: null;

        // Ambil seluruh siswa pada jurusan binaan ini
        $builder = $this->db->table('siswa')
            ->select('siswa.*, MAX(kelas.nama_kelas) as nama_kelas, MAX(jurusan.nama_jurusan) as nama_jurusan, MAX(dudi.nama_dudi) as nama_dudi, MAX(dudi.nama_pic) as nama_pic,
                      COALESCE(MAX(gm.nama_guru), MAX(gp.nama_guru), MAX(gmap.nama_guru)) as nama_pembimbing,
                      COALESCE(MAX(absen.status), "belum") as status_absen_hari_ini')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
            ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
            ->join('distribusi_manual dm', 'dm.siswa_id = siswa.id', 'left')
            ->join('guru gm', 'gm.id = dm.guru_id', 'left')
            ->join('distribusi_pkl dp', 'dp.siswa_id = siswa.id', 'left')
            ->join('guru gp', 'gp.id = dp.guru_id', 'left')
            ->join('mapping_dudi md', 'md.siswa_id = siswa.id', 'left')
            ->join('guru gmap', 'gmap.id = md.guru_id', 'left')
            ->join('absensi_pkl absen', 'absen.siswa_id = siswa.id AND absen.tanggal = "' . date('Y-m-d') . '"', 'left')
            ->whereIn('kelas.jurusan_id', $kaprogJurusanIds);

        if (!empty($jurusanPilih)) {
            $builder->where('kelas.jurusan_id', $jurusanPilih);
        }

        if (!empty($kelasPilih)) {
            $builder->where('siswa.id_kelas', $kelasPilih);
        }

        $allSiswa = $builder->groupBy('siswa.id')
                            ->orderBy('MAX(kelas.nama_kelas)', 'ASC')
                            ->orderBy('siswa.nama', 'ASC')
                            ->get()->getResult();

        // Statistik jurusan
        $totalSiswaJurusan     = count($allSiswa);
        $totalSudahDitempatkan = 0;
        $totalHadirHariIni     = 0;

        foreach ($allSiswa as $s) {
            if (!empty($s->dudi_id)) $totalSudahDitempatkan++;
            if ($s->status_absen_hari_ini === 'hadir') $totalHadirHariIni++;
        }

        return view('portal_guru/kaprog', [
            'title'                 => 'Monitoring Program Keahlian (Kaprog)',
            'jurusans'              => $jurusans,
            'kelas'                 => $kelas,
            'jurusanPilih'          => $jurusanPilih,
            'kelasPilih'            => $kelasPilih,
            'allSiswa'              => $allSiswa,
            'totalSiswaJurusan'     => $totalSiswaJurusan,
            'totalSudahDitempatkan' => $totalSudahDitempatkan,
            'totalHadirHariIni'     => $totalHadirHariIni
        ]);
    }
}
