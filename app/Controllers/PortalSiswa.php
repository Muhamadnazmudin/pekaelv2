<?php

namespace App\Controllers;

use App\Models\SekolahModel;

class PortalSiswa extends BaseController
{
    protected function getSiswaData()
    {
        $siswaId = session()->get('siswa_id');
        if (!$siswaId) {
            return null;
        }

        return $this->db->table('siswa')
            ->select('siswa.*, kelas.nama_kelas, kelas.tingkat, jurusan.nama_jurusan, 
                      dudi.id as dudi_id, dudi.nama_dudi, dudi.alamat as alamat_dudi, 
                      dudi.latitude as dudi_lat, dudi.longitude as dudi_long, dudi.radius_meter,
                      dudi.nama_pic, dudi.no_hp_pic,
                      COALESCE(gm.nama_guru, gp.nama_guru, gmap.nama_guru) as nama_pembimbing, 
                      COALESCE(gm.nip, gp.nip, gmap.nip) as nip_pembimbing,
                      COALESCE(gm.id, gp.id, gmap.id) as pembimbing_id')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
            ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
            ->join('distribusi_manual dm', 'dm.siswa_id = siswa.id', 'left')
            ->join('guru gm', 'gm.id = dm.guru_id', 'left')
            ->join('distribusi_pkl dp', 'dp.siswa_id = siswa.id', 'left')
            ->join('guru gp', 'gp.id = dp.guru_id', 'left')
            ->join('mapping_dudi md', 'md.siswa_id = siswa.id', 'left')
            ->join('guru gmap', 'gmap.id = md.guru_id', 'left')
            ->where('siswa.id', $siswaId)
            ->get()->getRow();
    }

    public function dashboard()
    {
        $siswa = $this->getSiswaData();
        if (!$siswa) {
            return redirect()->to(site_url('login'))->with('error', 'Sesi siswa tidak valid.');
        }

        $today = date('Y-m-d');
        $absenHariIni = $this->db->table('absensi_pkl')
            ->where('siswa_id', $siswa->id)
            ->where('tanggal', $today)
            ->get()->getRow();

        $bulanIni = date('Y-m');
        $totalHadirBulanIni = $this->db->table('absensi_pkl')
            ->where('siswa_id', $siswa->id)
            ->like('tanggal', $bulanIni, 'after')
            ->where('status', 'hadir')
            ->countAllResults();

        $totalJurnal = $this->db->table('jurnal_pkl')
            ->where('siswa_id', $siswa->id)
            ->countAllResults();

        $jurnalTerbaru = $this->db->table('jurnal_pkl')
            ->where('siswa_id', $siswa->id)
            ->orderBy('tanggal', 'DESC')
            ->limit(5)
            ->get()->getResult();

        $sekolah = (new SekolahModel())->getProfil();

        return view('portal_siswa/dashboard', [
            'title'              => 'Dashboard Siswa PKL',
            'siswa'              => $siswa,
            'absenHariIni'       => $absenHariIni,
            'totalHadirBulanIni' => $totalHadirBulanIni,
            'totalJurnal'        => $totalJurnal,
            'jurnalTerbaru'      => $jurnalTerbaru,
            'sekolah'            => $sekolah
        ]);
    }

    public function absensi()
    {
        $siswa = $this->getSiswaData();
        if (!$siswa) {
            return redirect()->to(site_url('login'));
        }

        $today = date('Y-m-d');
        $absenHariIni = $this->db->table('absensi_pkl')
            ->where('siswa_id', $siswa->id)
            ->where('tanggal', $today)
            ->get()->getRow();

        $riwayatAbsensi = $this->db->table('absensi_pkl')
            ->where('siswa_id', $siswa->id)
            ->orderBy('tanggal', 'DESC')
            ->limit(30)
            ->get()->getResult();

        return view('portal_siswa/absensi', [
            'title'          => 'Presensi Siswa PKL (Selfie & GPS)',
            'siswa'          => $siswa,
            'absenHariIni'   => $absenHariIni,
            'riwayatAbsensi' => $riwayatAbsensi,
            'today'          => $today
        ]);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        if (empty($lat1) || empty($lon1) || empty($lat2) || empty($lon2)) {
            return null;
        }
        $earthRadius = 6371000; // meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return (int) round($earthRadius * $c);
    }

    public function absen_masuk()
    {
        $siswa = $this->getSiswaData();
        if (!$siswa) {
            return redirect()->to(site_url('login'));
        }

        if (empty($siswa->dudi_id)) {
            return redirect()->back()->with('error', 'Anda belum ditempatkan di mitra DUDI manapun. Hubungi Pokja PKL.');
        }

        $today = date('Y-m-d');
        $now   = date('H:i:s');

        // Cek apakah sudah absen hari ini
        $cek = $this->db->table('absensi_pkl')
            ->where('siswa_id', $siswa->id)
            ->where('tanggal', $today)
            ->get()->getRow();

        if ($cek && !empty($cek->jam_masuk)) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absen masuk hari ini pada pukul ' . $cek->jam_masuk . '.');
        }

        $lat  = $this->request->getPost('latitude') ? (float)$this->request->getPost('latitude') : null;
        $long = $this->request->getPost('longitude') ? (float)$this->request->getPost('longitude') : null;

        // Validasi Jarak Geolocation jika titik DUDI sudah diset
        $jarak = null;
        if (!empty($siswa->dudi_lat) && !empty($siswa->dudi_long) && !empty($lat) && !empty($long)) {
            $jarak = $this->calculateDistance($siswa->dudi_lat, $siswa->dudi_long, $lat, $long);
            $maxRadius = (int)($siswa->radius_meter ?: 100);

            // Toleransi GPS akurasi smartphone + 50 meter
            if ($jarak > ($maxRadius + 50)) {
                return redirect()->back()->with('error', "Gagal Absen: Lokasi Anda berjarak {$jarak} meter dari DUDI (Batas radius: {$maxRadius} meter). Pastikan Anda berada di tempat PKL.");
            }
        }

        // Upload / Proses Foto Selfie
        $fotoName = $this->handleSelfieUpload('masuk', $siswa->id);

        $dataInsert = [
            'siswa_id'    => $siswa->id,
            'dudi_id'     => $siswa->dudi_id,
            'tanggal'     => $today,
            'jam_masuk'   => $now,
            'foto_masuk'  => $fotoName,
            'lat_masuk'   => $lat,
            'long_masuk'  => $long,
            'jarak_masuk' => $jarak,
            'status'      => 'hadir',
            'created_at'  => date('Y-m-d H:i:s')
        ];

        if ($cek) {
            $this->db->table('absensi_pkl')->where('id', $cek->id)->update($dataInsert);
        } else {
            $this->db->table('absensi_pkl')->insert($dataInsert);
        }

        $jarakInfo = ($jarak !== null) ? " (Jarak: {$jarak}m)" : " (Titik DUDI belum diset Pokja)";
        return redirect()->to(site_url('siswa/absensi'))->with('success', "Absen Masuk Berhasil dicatat pukul {$now}{$jarakInfo}. Selamat menjalankan aktivitas PKL!");
    }

    public function absen_pulang()
    {
        $siswa = $this->getSiswaData();
        if (!$siswa) {
            return redirect()->to(site_url('login'));
        }

        $today = date('Y-m-d');
        $now   = date('H:i:s');

        $cek = $this->db->table('absensi_pkl')
            ->where('siswa_id', $siswa->id)
            ->where('tanggal', $today)
            ->get()->getRow();

        if (!$cek || empty($cek->jam_masuk)) {
            return redirect()->back()->with('error', 'Anda belum melakukan absen masuk hari ini. Lakukan absen masuk terlebih dahulu.');
        }

        if (!empty($cek->jam_pulang)) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absen pulang hari ini pada pukul ' . $cek->jam_pulang . '.');
        }

        $lat  = $this->request->getPost('latitude') ? (float)$this->request->getPost('latitude') : null;
        $long = $this->request->getPost('longitude') ? (float)$this->request->getPost('longitude') : null;

        $jarak = null;
        if (!empty($siswa->dudi_lat) && !empty($siswa->dudi_long) && !empty($lat) && !empty($long)) {
            $jarak = $this->calculateDistance($siswa->dudi_lat, $siswa->dudi_long, $lat, $long);
            $maxRadius = (int)($siswa->radius_meter ?: 100);

            if ($jarak > ($maxRadius + 50)) {
                return redirect()->back()->with('error', "Gagal Absen Pulang: Lokasi Anda berjarak {$jarak} meter dari DUDI (Batas radius: {$maxRadius} meter).");
            }
        }

        $fotoName = $this->handleSelfieUpload('pulang', $siswa->id);

        $this->db->table('absensi_pkl')->where('id', $cek->id)->update([
            'jam_pulang'   => $now,
            'foto_pulang'  => $fotoName,
            'lat_pulang'   => $lat,
            'long_pulang'  => $long,
            'jarak_pulang' => $jarak
        ]);

        return redirect()->to(site_url('siswa/absensi'))->with('success', "Absen Pulang Berhasil dicatat pukul {$now}. Terima kasih atas dedikasi PKL hari ini!");
    }

    private function handleSelfieUpload($tipe, $siswaId)
    {
        $fotoBase64 = $this->request->getPost('foto_base64');
        $uploadDir = FCPATH . 'uploads/absensi/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        // 1. Cek jika data dikirim sebagai Base64 dari webcam canvas
        if (!empty($fotoBase64) && strpos($fotoBase64, 'data:image') === 0) {
            $parts = explode(',', $fotoBase64);
            $decoded = base64_decode($parts[1] ?? '');
            if ($decoded) {
                $filename = "selfie_{$siswaId}_{$tipe}_" . date('Ymd_His') . ".jpg";
                file_put_contents($uploadDir . $filename, $decoded);
                return $filename;
            }
        }

        // 2. Cek jika dikirim via input file standard (kamera HP)
        $file = $this->request->getFile('foto_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $filename = "selfie_{$siswaId}_{$tipe}_" . date('Ymd_His') . '.' . $file->getExtension();
            $file->move($uploadDir, $filename);
            return $filename;
        }

        return null;
    }

    public function jurnal()
    {
        $siswa = $this->getSiswaData();
        if (!$siswa) {
            return redirect()->to(site_url('login'));
        }

        $daftarJurnal = $this->db->table('jurnal_pkl')
            ->select('jurnal_pkl.*, guru.nama_guru as nama_pembimbing')
            ->join('guru', 'guru.id = jurnal_pkl.guru_id', 'left')
            ->where('jurnal_pkl.siswa_id', $siswa->id)
            ->orderBy('jurnal_pkl.tanggal', 'DESC')
            ->get()->getResult();

        return view('portal_siswa/jurnal', [
            'title'        => 'Laporan & Jurnal Harian PKL',
            'siswa'        => $siswa,
            'daftarJurnal' => $daftarJurnal
        ]);
    }

    public function jurnal_simpan()
    {
        $siswa = $this->getSiswaData();
        if (!$siswa) {
            return redirect()->to(site_url('login'));
        }

        $tanggal  = $this->request->getPost('tanggal') ?: date('Y-m-d');
        $kegiatan = trim($this->request->getPost('kegiatan') ?? '');
        $hasil    = trim($this->request->getPost('hasil') ?? '');

        if (empty($kegiatan)) {
            return redirect()->back()->withInput()->with('error', 'Uraian kegiatan harian wajib diisi.');
        }

        // Upload Foto Dokumentasi Kegiatan (Opsional)
        $fotoName = null;
        $file = $this->request->getFile('foto_kegiatan');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadDir = FCPATH . 'uploads/jurnal/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            $fotoName = "jurnal_{$siswa->id}_" . date('Ymd_His') . '.' . $file->getExtension();
            $file->move($uploadDir, $fotoName);
        }

        $this->db->table('jurnal_pkl')->insert([
            'siswa_id'           => $siswa->id,
            'dudi_id'            => $siswa->dudi_id,
            'guru_id'            => $siswa->pembimbing_id ?: null,
            'tanggal'            => $tanggal,
            'kegiatan'           => $kegiatan,
            'hasil'              => $hasil,
            'foto'               => $fotoName,
            'status_verifikasi'  => 'menunggu',
            'created_at'         => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(site_url('siswa/jurnal'))->with('success', 'Jurnal harian PKL berhasil disimpan dan diteruskan ke guru pembimbing.');
    }

    public function jurnal_edit($id)
    {
        $siswa = $this->getSiswaData();
        if (!$siswa) {
            return redirect()->to(site_url('login'));
        }

        $jurnal = $this->db->table('jurnal_pkl')
            ->where('id', $id)
            ->where('siswa_id', $siswa->id)
            ->get()->getRow();

        if (!$jurnal) {
            return redirect()->to(site_url('siswa/jurnal'))->with('error', 'Laporan jurnal tidak ditemukan.');
        }

        if ($jurnal->status_verifikasi === 'disetujui') {
            return redirect()->to(site_url('siswa/jurnal'))->with('error', 'Laporan jurnal ini sudah diverifikasi & disetujui pembimbing sehingga terkunci dari perubahan.');
        }

        $tanggal  = $this->request->getPost('tanggal') ?: $jurnal->tanggal;
        $kegiatan = trim($this->request->getPost('kegiatan') ?? '');
        $hasil    = trim($this->request->getPost('hasil') ?? '');

        if (empty($kegiatan)) {
            return redirect()->back()->withInput()->with('error', 'Uraian kegiatan harian wajib diisi.');
        }

        $updateData = [
            'tanggal'    => $tanggal,
            'kegiatan'   => $kegiatan,
            'hasil'      => $hasil,
            'guru_id'    => $siswa->pembimbing_id ?: $jurnal->guru_id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Jika status sebelumnya 'revisi', ubah kembali ke 'menunggu' agar pembimbing tahu sudah diperbaiki
        if ($jurnal->status_verifikasi === 'revisi') {
            $updateData['status_verifikasi'] = 'menunggu';
        }

        // Cek jika ada upload foto baru
        $file = $this->request->getFile('foto_kegiatan');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadDir = FCPATH . 'uploads/jurnal/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            if (!empty($jurnal->foto) && file_exists($uploadDir . $jurnal->foto)) {
                @unlink($uploadDir . $jurnal->foto);
            }
            $fotoName = "jurnal_{$siswa->id}_" . date('Ymd_His') . '.' . $file->getExtension();
            $file->move($uploadDir, $fotoName);
            $updateData['foto'] = $fotoName;
        }

        $this->db->table('jurnal_pkl')->where('id', $id)->update($updateData);

        return redirect()->to(site_url('siswa/jurnal'))->with('success', 'Laporan jurnal harian berhasil diperbarui.');
    }

    public function jurnal_hapus($id)
    {
        $siswa = $this->getSiswaData();
        if (!$siswa) {
            return redirect()->to(site_url('login'));
        }

        $jurnal = $this->db->table('jurnal_pkl')
            ->where('id', $id)
            ->where('siswa_id', $siswa->id)
            ->get()->getRow();

        if ($jurnal) {
            if ($jurnal->status_verifikasi === 'disetujui') {
                return redirect()->to(site_url('siswa/jurnal'))->with('error', 'Laporan jurnal ini sudah diverifikasi & disetujui pembimbing sehingga terkunci dan tidak dapat dihapus.');
            }

            if (!empty($jurnal->foto)) {
                $filePath = FCPATH . 'uploads/jurnal/' . $jurnal->foto;
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
            $this->db->table('jurnal_pkl')->where('id', $id)->delete();
            return redirect()->to(site_url('siswa/jurnal'))->with('success', 'Laporan jurnal harian berhasil dihapus.');
        }

        return redirect()->to(site_url('siswa/jurnal'))->with('error', 'Jurnal tidak ditemukan.');
    }
}
