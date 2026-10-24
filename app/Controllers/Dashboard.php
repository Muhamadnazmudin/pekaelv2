<?php

namespace App\Controllers;

use App\Models\DapodikModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $tahun = $this->db->table('tahun_ajaran')->where('status', 'aktif')->get()->getRow();
        $tahunId = $tahun ? $tahun->id : 0;

        // Total Guru
        $totalGuru = $this->db->table('guru')->countAllResults();

        // Guru Pembimbing (punya alokasi jam di kelas PKL)
        $guruPembimbing = $this->db->table('pembagian_jam')
            ->distinct()
            ->select('pembagian_jam.guru_id')
            ->join('kelas', 'kelas.id = pembagian_jam.kelas_id')
            ->where('kelas.status_pkl', 'ya')
            ->where('pembagian_jam.tahun_id', $tahunId)
            ->countAllResults();

        // Total Siswa PKL (status_pkl = 'ya')
        $totalSiswa = $this->db->table('siswa')
            ->join('kelas', 'kelas.id = siswa.id_kelas')
            ->where('kelas.status_pkl', 'ya')
            ->where('siswa.id_tahun', $tahunId)
            ->countAllResults();

        // Total Rombel PKL
        $totalRombel = $this->db->table('kelas')
            ->where('status_pkl', 'ya')
            ->countAllResults();

        $totalRombelSemua = $this->db->table('kelas')->countAllResults();

        // Koefisien PKL Aktif
        $koefRow = $this->db->table('koefisien_pkl')
            ->where('tahun_id', $tahunId)
            ->orderBy('id', 'DESC')
            ->get()->getRow();
        $koefisien = $koefRow ? (float)$koefRow->koefisien : 0.0;
        $jpWajib = $koefRow ? (int)$koefRow->jp : 46;

        // Siswa Belum Dapat Pembimbing (cek distribusi_manual dan distribusi_pkl)
        $belumPembimbing = $this->db->table('siswa')
            ->join('kelas', 'kelas.id = siswa.id_kelas')
            ->join('distribusi_manual', 'distribusi_manual.siswa_id = siswa.id', 'left')
            ->where('kelas.status_pkl', 'ya')
            ->where('siswa.id_tahun', $tahunId)
            ->where('distribusi_manual.id IS NULL', null, false)
            ->countAllResults();

        // Siswa Belum Dapat DUDI
        $belumDudi = $this->db->table('siswa')
            ->join('kelas', 'kelas.id = siswa.id_kelas')
            ->where('kelas.status_pkl', 'ya')
            ->where('siswa.id_tahun', $tahunId)
            ->groupStart()
                ->where('siswa.dudi_id IS NULL', null, false)
                ->orWhere('siswa.dudi_id', 0)
            ->groupEnd()
            ->countAllResults();

        // Total DUDI Aktif
        $totalDudi = $this->db->table('dudi')
            ->where('status_kerjasama', 'aktif')
            ->countAllResults();

        // Monitoring per Rombel PKL
        $rombelList = $this->db->table('kelas')
            ->where('status_pkl', 'ya')
            ->orderBy('nama_kelas', 'ASC')
            ->get()->getResult();

        $monitoringRombel = [];
        foreach ($rombelList as $r) {
            $totalSiswaRombel = $this->db->table('siswa')
                ->where('id_kelas', $r->id)
                ->where('id_tahun', $tahunId)
                ->countAllResults();

            $belumDudiRombel = $this->db->table('siswa')
                ->where('id_kelas', $r->id)
                ->where('id_tahun', $tahunId)
                ->groupStart()
                    ->where('dudi_id IS NULL', null, false)
                    ->orWhere('dudi_id', 0)
                ->groupEnd()
                ->countAllResults();

            $belumPembimbingRombel = $this->db->table('siswa')
                ->join('distribusi_manual', 'distribusi_manual.siswa_id = siswa.id', 'left')
                ->where('siswa.id_kelas', $r->id)
                ->where('siswa.id_tahun', $tahunId)
                ->where('distribusi_manual.id IS NULL', null, false)
                ->countAllResults();

            // Total JP Terbagi di Rombel ini
            $totalJpRombel = $this->db->table('pembagian_jam')
                ->selectSum('jumlah_jam')
                ->where('kelas_id', $r->id)
                ->where('tahun_id', $tahunId)
                ->get()->getRow()->jumlah_jam ?? 0;

            $monitoringRombel[] = (object)[
                'id'                => $r->id,
                'nama_kelas'        => $r->nama_kelas,
                'tingkat'           => $r->tingkat,
                'total_siswa'       => $totalSiswaRombel,
                'belum_dudi'        => $belumDudiRombel,
                'belum_pembimbing'  => $belumPembimbingRombel,
                'total_jp'          => (int)$totalJpRombel,
                'status_jp'         => ((int)$totalJpRombel === $jpWajib) ? 'sesuai' : 'belum'
            ];
        }

        // Cek status Dapodik lokal
        $dapodikModel = new DapodikModel();
        $dapoStatus = $dapodikModel->testConnection();

        // Metrik Monitoring PKL Real-Time
        $today = date('Y-m-d');
        $totalAbsenHariIni = $this->db->table('absensi_pkl')->where('tanggal', $today)->where('status', 'hadir')->countAllResults();
        $jurnalMenunggu    = $this->db->table('jurnal_pkl')->where('status_verifikasi', 'menunggu')->countAllResults();
        $totalJurnal       = $this->db->table('jurnal_pkl')->countAllResults();

        return template('admin/dashboard', [
            'title'               => 'Dashboard JJM PKL',
            'tahun'               => $tahun,
            'total_guru'          => $totalGuru,
            'guru_pembimbing'     => $guruPembimbing,
            'total_siswa'         => $totalSiswa,
            'total_rombel'        => $totalRombel,
            'total_rombel_semua'  => $totalRombelSemua,
            'koefisien'           => $koefisien,
            'jp_wajib'            => $jpWajib,
            'belum_pembimbing'    => $belumPembimbing,
            'belum_dudi'          => $belumDudi,
            'total_dudi'          => $totalDudi,
            'monitoring_rombel'   => $monitoringRombel,
            'dapodik_status'      => $dapoStatus,
            'total_absen_hari_ini'=> $totalAbsenHariIni,
            'jurnal_menunggu'     => $jurnalMenunggu,
            'total_jurnal'        => $totalJurnal
        ]);
    }
}
