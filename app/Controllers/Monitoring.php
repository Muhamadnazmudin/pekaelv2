<?php

namespace App\Controllers;

use Config\Database;

class Monitoring extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index()
    {
        return redirect()->to(site_url('monitoring/absensi'));
    }

    /**
     * Monitoring Presensi Siswa PKL (Global Admin View)
     */
    public function absensi()
    {
        $tanggal        = $this->request->getGet('tanggal') ?: date('Y-m-d');
        $jurusanId      = $this->request->getGet('jurusan_id') ?: '';
        $kelasId        = $this->request->getGet('kelas_id') ?: '';
        $dudiId         = $this->request->getGet('dudi_id') ?: '';
        $statusFilter   = $this->request->getGet('status') ?: '';
        $search         = trim($this->request->getGet('q') ?? '');

        // Master Dropdown Data
        $jurusanList = $this->db->table('jurusan')->orderBy('nama_jurusan', 'ASC')->get()->getResult();
        $kelasList   = $this->db->table('kelas')->orderBy('nama_kelas', 'ASC')->get()->getResult();
        $dudiList    = $this->db->table('dudi')->orderBy('nama_dudi', 'ASC')->get()->getResult();

        // Query Siswa dengan status absensi pada tanggal tersebut
        $builder = $this->db->table('siswa')
            ->select('siswa.id, siswa.nama, siswa.nisn, siswa.jenis_kelamin, siswa.dudi_id, siswa.id_kelas,
                      MAX(kelas.nama_kelas) as nama_kelas,
                      MAX(jurusan.id) as jurusan_id,
                      MAX(jurusan.nama_jurusan) as nama_jurusan,
                      MAX(dudi.nama_dudi) as nama_dudi,
                      MAX(dudi.alamat) as alamat_dudi,
                      COALESCE(MAX(gm.nama_guru), MAX(gp.nama_guru), MAX(gmap.nama_guru)) as nama_pembimbing,
                      MAX(absen.id) as absensi_id,
                      MAX(absen.jam_masuk) as jam_masuk,
                      MAX(absen.jam_pulang) as jam_pulang,
                      MAX(absen.foto_masuk) as foto_masuk,
                      MAX(absen.foto_pulang) as foto_pulang,
                      MAX(absen.lat_masuk) as lat_masuk,
                      MAX(absen.long_masuk) as long_masuk,
                      MAX(absen.lat_pulang) as lat_pulang,
                      MAX(absen.long_pulang) as long_pulang,
                      MAX(absen.jarak_masuk) as jarak_masuk,
                      MAX(absen.jarak_pulang) as jarak_pulang,
                      COALESCE(MAX(absen.status), "belum") as status_absen,
                      MAX(absen.keterangan) as keterangan_absen')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
            ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
            ->join('distribusi_manual dm', 'dm.siswa_id = siswa.id', 'left')
            ->join('guru gm', 'gm.id = dm.guru_id', 'left')
            ->join('distribusi_pkl dp', 'dp.siswa_id = siswa.id', 'left')
            ->join('guru gp', 'gp.id = dp.guru_id', 'left')
            ->join('mapping_dudi md', 'md.siswa_id = siswa.id', 'left')
            ->join('guru gmap', 'gmap.id = md.guru_id', 'left')
            ->join('absensi_pkl absen', 'absen.siswa_id = siswa.id AND absen.tanggal = "' . $this->db->escapeString($tanggal) . '"', 'left');

        if (!empty($jurusanId)) {
            $builder->where('kelas.jurusan_id', $jurusanId);
        }

        if (!empty($kelasId)) {
            $builder->where('siswa.id_kelas', $kelasId);
        }

        if (!empty($dudiId)) {
            $builder->where('siswa.dudi_id', $dudiId);
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('siswa.nama', $search)
                ->orLike('siswa.nisn', $search)
                ->orLike('dudi.nama_dudi', $search)
                ->groupEnd();
        }

        $allData = $builder->groupBy('siswa.id')
            ->orderBy('MAX(kelas.nama_kelas)', 'ASC')
            ->orderBy('siswa.nama', 'ASC')
            ->get()->getResult();

        // Hitung statistik
        $totalSiswa = count($allData);
        $totalHadir = 0;
        $totalIzin  = 0;
        $totalSakit = 0;
        $totalBelum = 0;

        $filteredData = [];
        foreach ($allData as $row) {
            $status = $row->status_absen;
            if ($status === 'hadir') {
                $totalHadir++;
            } elseif ($status === 'izin') {
                $totalIzin++;
            } elseif ($status === 'sakit') {
                $totalSakit++;
            } else {
                $totalBelum++;
            }

            // Terapkan filter status jika dipilih
            if (!empty($statusFilter)) {
                if ($statusFilter === 'belum' && $status !== 'belum' && $status !== 'alpha') {
                    continue;
                }
                if ($statusFilter !== 'belum' && $status !== $statusFilter) {
                    continue;
                }
            }

            $filteredData[] = $row;
        }

        $persenHadir = $totalSiswa > 0 ? round(($totalHadir / $totalSiswa) * 100, 1) : 0;

        $data = [
            'title'           => 'Monitoring Presensi Siswa PKL',
            'tanggal'         => $tanggal,
            'jurusanList'     => $jurusanList,
            'kelasList'       => $kelasList,
            'dudiList'        => $dudiList,
            'selectedJurusan' => $jurusanId,
            'selectedKelas'   => $kelasId,
            'selectedDudi'    => $dudiId,
            'selectedStatus'  => $statusFilter,
            'searchQuery'     => $search,
            'records'         => $filteredData,
            'stats'           => (object)[
                'totalSiswa'  => $totalSiswa,
                'totalHadir'  => $totalHadir,
                'totalIzin'   => $totalIzin,
                'totalSakit'  => $totalSakit,
                'totalBelum'  => $totalBelum,
                'persenHadir' => $persenHadir
            ]
        ];

        return template('admin/monitoring/absensi', $data);
    }

    /**
     * Monitoring Jurnal Siswa PKL (Global Admin View)
     */
    public function jurnal()
    {
        $jurusanId    = $this->request->getGet('jurusan_id') ?: '';
        $kelasId      = $this->request->getGet('kelas_id') ?: '';
        $guruId       = $this->request->getGet('guru_id') ?: '';
        $statusFilter = $this->request->getGet('status') ?: '';
        $tanggal      = $this->request->getGet('tanggal') ?: '';
        $search       = trim($this->request->getGet('q') ?? '');

        // Dropdown data
        $jurusanList = $this->db->table('jurusan')->orderBy('nama_jurusan', 'ASC')->get()->getResult();
        $kelasList   = $this->db->table('kelas')->orderBy('nama_kelas', 'ASC')->get()->getResult();
        $guruList    = $this->db->table('guru')->orderBy('nama_guru', 'ASC')->get()->getResult();

        // Hitung Counter Global Status Jurnal
        $countMenunggu  = $this->db->table('jurnal_pkl')->where('status_verifikasi', 'menunggu')->countAllResults();
        $countDisetujui = $this->db->table('jurnal_pkl')->where('status_verifikasi', 'disetujui')->countAllResults();
        $countRevisi    = $this->db->table('jurnal_pkl')->where('status_verifikasi', 'revisi')->countAllResults();
        $countTotal     = $this->db->table('jurnal_pkl')->countAllResults();

        // Main Query Jurnal dengan agregasi penuh agar 100% kompatibel dengan sql_mode=only_full_group_by
        $builder = $this->db->table('jurnal_pkl')
            ->select('jurnal_pkl.id,
                      MAX(jurnal_pkl.siswa_id) as siswa_id,
                      MAX(jurnal_pkl.dudi_id) as dudi_id,
                      MAX(jurnal_pkl.guru_id) as guru_id,
                      MAX(jurnal_pkl.tanggal) as tanggal,
                      MAX(jurnal_pkl.kegiatan) as kegiatan,
                      MAX(jurnal_pkl.hasil) as hasil,
                      MAX(jurnal_pkl.foto) as foto,
                      MAX(jurnal_pkl.status_verifikasi) as status_verifikasi,
                      MAX(jurnal_pkl.catatan_pembimbing) as catatan_pembimbing,
                      MAX(jurnal_pkl.waktu_verifikasi) as waktu_verifikasi,
                      MAX(jurnal_pkl.created_at) as created_at,
                      MAX(siswa.nama) as nama_siswa,
                      MAX(siswa.nisn) as nisn,
                      MAX(kelas.nama_kelas) as nama_kelas,
                      MAX(jurusan.nama_jurusan) as nama_jurusan,
                      MAX(dudi.nama_dudi) as nama_dudi,
                      MAX(gv.nama_guru) as nama_verifikator,
                      COALESCE(MAX(gm.nama_guru), MAX(gp.nama_guru), MAX(gmap.nama_guru)) as nama_pembimbing')
            ->join('siswa', 'siswa.id = jurnal_pkl.siswa_id')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
            ->join('dudi', 'dudi.id = jurnal_pkl.dudi_id', 'left')
            ->join('guru gv', 'gv.id = jurnal_pkl.guru_id', 'left')
            ->join('distribusi_manual dm', 'dm.siswa_id = siswa.id', 'left')
            ->join('guru gm', 'gm.id = dm.guru_id', 'left')
            ->join('distribusi_pkl dp', 'dp.siswa_id = siswa.id', 'left')
            ->join('guru gp', 'gp.id = dp.guru_id', 'left')
            ->join('mapping_dudi md', 'md.siswa_id = siswa.id', 'left')
            ->join('guru gmap', 'gmap.id = md.guru_id', 'left');

        if (!empty($jurusanId)) {
            $builder->where('kelas.jurusan_id', $jurusanId);
        }

        if (!empty($kelasId)) {
            $builder->where('siswa.id_kelas', $kelasId);
        }

        if (!empty($guruId)) {
            $builder->groupStart()
                ->where('dm.guru_id', $guruId)
                ->orWhere('dp.guru_id', $guruId)
                ->orWhere('md.guru_id', $guruId)
                ->orWhere('jurnal_pkl.guru_id', $guruId)
                ->groupEnd();
        }

        if (!empty($statusFilter)) {
            $builder->where('jurnal_pkl.status_verifikasi', $statusFilter);
        }

        if (!empty($tanggal)) {
            $builder->where('jurnal_pkl.tanggal', $tanggal);
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('siswa.nama', $search)
                ->orLike('siswa.nisn', $search)
                ->orLike('jurnal_pkl.kegiatan', $search)
                ->orLike('jurnal_pkl.hasil', $search)
                ->orLike('dudi.nama_dudi', $search)
                ->groupEnd();
        }

        $jurnals = $builder->groupBy('jurnal_pkl.id')
            ->orderBy('MAX(jurnal_pkl.tanggal)', 'DESC')
            ->orderBy('jurnal_pkl.id', 'DESC')
            ->get()->getResult();

        $data = [
            'title'           => 'Monitoring & Audit Jurnal Siswa PKL',
            'jurnals'         => $jurnals,
            'jurusanList'     => $jurusanList,
            'kelasList'       => $kelasList,
            'guruList'        => $guruList,
            'selectedJurusan' => $jurusanId,
            'selectedKelas'   => $kelasId,
            'selectedGuru'    => $guruId,
            'selectedStatus'  => $statusFilter,
            'selectedTanggal' => $tanggal,
            'searchQuery'     => $search,
            'stats'           => (object)[
                'total'     => $countTotal,
                'menunggu'  => $countMenunggu,
                'disetujui' => $countDisetujui,
                'revisi'    => $countRevisi
            ]
        ];

        return template('admin/monitoring/jurnal', $data);
    }

    /**
     * Super Admin Override: Verifikasi / Kunci / Minta Revisi Jurnal
     */
    public function jurnal_verifikasi($id)
    {
        $status  = $this->request->getPost('status_verifikasi') ?: 'disetujui';
        $catatan = trim($this->request->getPost('catatan_pembimbing') ?? '');

        $catatanFormatted = $catatan ? "[Admin PKL] " . $catatan : null;

        $this->db->table('jurnal_pkl')
            ->where('id', $id)
            ->update([
                'status_verifikasi'  => $status,
                'catatan_pembimbing' => $catatanFormatted,
                'waktu_verifikasi'   => date('Y-m-d H:i:s')
            ]);

        $label = ($status === 'disetujui') ? 'Disetujui & Terkunci' : (($status === 'revisi') ? 'Diminta Revisi' : 'Kembali ke Menunggu');
        return redirect()->back()->with('success', "Status jurnal berhasil diperbarui oleh Admin menjadi: <strong>{$label}</strong>.");
    }

    /**
     * Super Admin Override: Buka Kunci Jurnal (Unlock)
     */
    public function jurnal_unlock($id)
    {
        $jurnal = $this->db->table('jurnal_pkl')->where('id', $id)->get()->getRow();
        if (!$jurnal) {
            return redirect()->back()->with('error', 'Data jurnal tidak ditemukan.');
        }

        $this->db->table('jurnal_pkl')
            ->where('id', $id)
            ->update([
                'status_verifikasi'  => 'menunggu',
                'catatan_pembimbing' => trim(($jurnal->catatan_pembimbing ?? '') . " [Kunci dibuka oleh Admin pada " . date('d/m/Y H:i') . "]")
            ]);

        return redirect()->back()->with('success', 'Kunci laporan jurnal berhasil dibuka! Siswa kini dapat mengedit atau menghapus laporan tersebut kembali.');
    }

    /**
     * Super Admin: Hapus Jurnal
     */
    public function jurnal_hapus($id)
    {
        $jurnal = $this->db->table('jurnal_pkl')->where('id', $id)->get()->getRow();
        if (!$jurnal) {
            return redirect()->back()->with('error', 'Data jurnal tidak ditemukan.');
        }

        if (!empty($jurnal->foto)) {
            $file = FCPATH . 'uploads/jurnal/' . $jurnal->foto;
            if (file_exists($file)) {
                @unlink($file);
            }
        }

        $this->db->table('jurnal_pkl')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Laporan jurnal berhasil dihapus dari sistem oleh Admin.');
    }

    /**
     * Cetak Lembar Presensi Siswa PKL (Printable View)
     */
    public function cetak_absensi()
    {
        $tanggal        = $this->request->getGet('tanggal') ?: date('Y-m-d');
        $jurusanId      = $this->request->getGet('jurusan_id') ?: '';
        $kelasId        = $this->request->getGet('kelas_id') ?: '';
        $dudiId         = $this->request->getGet('dudi_id') ?: '';

        $sekolah = $this->db->table('sekolah')->limit(1)->get()->getRow();
        $tahunAktif = $this->db->table('tahun_ajaran')->where('status', 'aktif')->get()->getRow();

        $builder = $this->db->table('siswa')
            ->select('siswa.nama, siswa.nisn, siswa.jenis_kelamin,
                      MAX(kelas.nama_kelas) as nama_kelas,
                      MAX(jurusan.nama_jurusan) as nama_jurusan,
                      MAX(dudi.nama_dudi) as nama_dudi,
                      COALESCE(MAX(gm.nama_guru), MAX(gp.nama_guru), MAX(gmap.nama_guru)) as nama_pembimbing,
                      MAX(absen.jam_masuk) as jam_masuk,
                      MAX(absen.jam_pulang) as jam_pulang,
                      COALESCE(MAX(absen.status), "belum") as status_absen,
                      MAX(absen.keterangan) as keterangan_absen')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
            ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
            ->join('distribusi_manual dm', 'dm.siswa_id = siswa.id', 'left')
            ->join('guru gm', 'gm.id = dm.guru_id', 'left')
            ->join('distribusi_pkl dp', 'dp.siswa_id = siswa.id', 'left')
            ->join('guru gp', 'gp.id = dp.guru_id', 'left')
            ->join('mapping_dudi md', 'md.siswa_id = siswa.id', 'left')
            ->join('guru gmap', 'gmap.id = md.guru_id', 'left')
            ->join('absensi_pkl absen', 'absen.siswa_id = siswa.id AND absen.tanggal = "' . $this->db->escapeString($tanggal) . '"', 'left');

        if (!empty($jurusanId)) $builder->where('kelas.jurusan_id', $jurusanId);
        if (!empty($kelasId)) $builder->where('siswa.id_kelas', $kelasId);
        if (!empty($dudiId)) $builder->where('siswa.dudi_id', $dudiId);

        $records = $builder->groupBy('siswa.id')
            ->orderBy('MAX(kelas.nama_kelas)', 'ASC')
            ->orderBy('siswa.nama', 'ASC')
            ->get()->getResult();

        return view('admin/monitoring/cetak_absensi', [
            'tanggal'    => $tanggal,
            'sekolah'    => $sekolah,
            'tahunAktif' => $tahunAktif,
            'records'    => $records
        ]);
    }

    /**
     * Cetak Rekap Jurnal Siswa PKL (Printable View)
     */
    public function cetak_jurnal()
    {
        $jurusanId    = $this->request->getGet('jurusan_id') ?: '';
        $kelasId      = $this->request->getGet('kelas_id') ?: '';
        $statusFilter = $this->request->getGet('status') ?: '';
        $tanggal      = $this->request->getGet('tanggal') ?: '';

        $sekolah = $this->db->table('sekolah')->limit(1)->get()->getRow();
        $tahunAktif = $this->db->table('tahun_ajaran')->where('status', 'aktif')->get()->getRow();

        $builder = $this->db->table('jurnal_pkl')
            ->select('jurnal_pkl.id,
                      MAX(jurnal_pkl.tanggal) as tanggal,
                      MAX(jurnal_pkl.kegiatan) as kegiatan,
                      MAX(jurnal_pkl.hasil) as hasil,
                      MAX(jurnal_pkl.status_verifikasi) as status_verifikasi,
                      MAX(siswa.nama) as nama_siswa,
                      MAX(siswa.nisn) as nisn,
                      MAX(kelas.nama_kelas) as nama_kelas,
                      MAX(jurusan.nama_jurusan) as nama_jurusan,
                      MAX(dudi.nama_dudi) as nama_dudi,
                      MAX(gv.nama_guru) as nama_verifikator,
                      COALESCE(MAX(gm.nama_guru), MAX(gp.nama_guru), MAX(gmap.nama_guru)) as nama_pembimbing')
            ->join('siswa', 'siswa.id = jurnal_pkl.siswa_id')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
            ->join('dudi', 'dudi.id = jurnal_pkl.dudi_id', 'left')
            ->join('guru gv', 'gv.id = jurnal_pkl.guru_id', 'left')
            ->join('distribusi_manual dm', 'dm.siswa_id = siswa.id', 'left')
            ->join('guru gm', 'gm.id = dm.guru_id', 'left')
            ->join('distribusi_pkl dp', 'dp.siswa_id = siswa.id', 'left')
            ->join('guru gp', 'gp.id = dp.guru_id', 'left')
            ->join('mapping_dudi md', 'md.siswa_id = siswa.id', 'left')
            ->join('guru gmap', 'gmap.id = md.guru_id', 'left');

        if (!empty($jurusanId)) $builder->where('kelas.jurusan_id', $jurusanId);
        if (!empty($kelasId)) $builder->where('siswa.id_kelas', $kelasId);
        if (!empty($statusFilter)) $builder->where('jurnal_pkl.status_verifikasi', $statusFilter);
        if (!empty($tanggal)) $builder->where('jurnal_pkl.tanggal', $tanggal);

        $jurnals = $builder->groupBy('jurnal_pkl.id')
            ->orderBy('MAX(jurnal_pkl.tanggal)', 'DESC')
            ->orderBy('jurnal_pkl.id', 'DESC')
            ->get()->getResult();

        return view('admin/monitoring/cetak_jurnal', [
            'tanggal'    => $tanggal,
            'sekolah'    => $sekolah,
            'tahunAktif' => $tahunAktif,
            'jurnals'    => $jurnals
        ]);
    }
}
