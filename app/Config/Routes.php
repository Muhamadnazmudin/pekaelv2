<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Root and Auth Routes
$routes->get('/', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('login', 'Auth::login');
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::process');
$routes->post('auth/process', 'Auth::process');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('logout', 'Auth::logout');

// Public Student Verification / Status Check
$routes->get('cek', 'Cek::index');
$routes->post('cek/hasil', 'Cek::hasil');

// Protected Admin & Operator Routes
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');

    // Integrasi Dapodik (Read-Only)
    $routes->get('dapodik', 'Dapodik::index');
    $routes->get('dapodik/test', 'Dapodik::test');
    $routes->get('dapodik/tarik_sekolah', 'Dapodik::tarik_sekolah');
    $routes->get('dapodik/tarik_jurusan', 'Dapodik::tarik_jurusan');
    $routes->get('dapodik/tarik_kelas', 'Dapodik::tarik_kelas');
    $routes->get('dapodik/tarik_siswa', 'Dapodik::tarik_siswa');
    $routes->get('dapodik/tarik_dudi', 'Dapodik::tarik_dudi');
    $routes->get('dapodik/tarik_guru', 'Dapodik::tarik_guru');
    $routes->get('dapodik/tarik_semua', 'Dapodik::tarik_semua');

    // Profil Sekolah
    $routes->get('sekolah', 'Sekolah::index');
    $routes->post('sekolah/update', 'Sekolah::update');

    // Tahun Ajaran
    $routes->get('tahun', 'Tahun::index');
    $routes->post('tahun/tambah', 'Tahun::tambah');
    $routes->get('tahun/set_aktif/(:num)', 'Tahun::set_aktif/$1');
    $routes->get('tahun/hapus/(:num)', 'Tahun::hapus/$1');

    // Jurusan
    $routes->get('jurusan', 'Jurusan::index');
    $routes->post('jurusan/tambah', 'Jurusan::tambah');
    $routes->post('jurusan/edit/(:num)', 'Jurusan::edit/$1');
    $routes->get('jurusan/hapus/(:num)', 'Jurusan::hapus/$1');

    // Kelas / Rombel
    $routes->get('kelas', 'Kelas::index');
    $routes->post('kelas/tambah', 'Kelas::tambah');
    $routes->post('kelas/edit/(:num)', 'Kelas::edit/$1');
    $routes->get('kelas/toggle_pkl/(:num)', 'Kelas::toggle_pkl/$1');
    $routes->get('kelas/hapus/(:num)', 'Kelas::hapus/$1');

    // Guru
    $routes->get('guru', 'Guru::index');
    $routes->post('guru/tambah', 'Guru::tambah');
    $routes->post('guru/edit/(:num)', 'Guru::edit/$1');
    $routes->get('guru/hapus/(:num)', 'Guru::hapus/$1');
    $routes->post('guru/import', 'Guru::import_excel');
    $routes->post('guru/import_excel', 'Guru::import_excel');
    $routes->get('guru/template', 'Guru::download_template');
    $routes->get('guru/download_template', 'Guru::download_template');

    // Siswa
    $routes->get('siswa', 'Siswa::index');
    $routes->post('siswa/tambah', 'Siswa::tambah');
    $routes->post('siswa/edit/(:num)', 'Siswa::edit/$1');
    $routes->get('siswa/hapus/(:num)', 'Siswa::hapus/$1');
    $routes->post('siswa/import', 'Siswa::import_excel');
    $routes->post('siswa/import_excel', 'Siswa::import_excel');
    $routes->get('siswa/template', 'Siswa::download_template');
    $routes->get('siswa/download_template', 'Siswa::download_template');

    // DUDI Mitra PKL & MoU
    $routes->get('dudi', 'Dudi::index');
    $routes->post('dudi/tambah', 'Dudi::tambah');
    $routes->post('dudi/edit/(:num)', 'Dudi::edit/$1');
    $routes->get('dudi/hapus/(:num)', 'Dudi::hapus/$1');
    $routes->post('dudi/import', 'Dudi::import_excel');
    $routes->post('dudi/import_excel', 'Dudi::import_excel');
    $routes->get('dudi/template', 'Dudi::download_template');
    $routes->get('dudi/download_template', 'Dudi::download_template');

    // Mapping DUDI
    $routes->get('mappingdudi', 'Mappingdudi::index');
    $routes->post('mappingdudi/simpan', 'Mappingdudi::simpan');
    $routes->post('mappingdudi/pindah', 'Mappingdudi::pindah');
    $routes->get('mappingdudi/hapus/(:num)', 'Mappingdudi::hapus/$1');
    $routes->get('mappingdudi/reset/(:num)', 'Mappingdudi::hapus/$1');
    $routes->get('mappingdudi/riwayat/(:num)', 'Mappingdudi::riwayat/$1');
    $routes->get('mappingdudi/search_dudi', 'Mappingdudi::search_dudi');

    // Koefisien PKL
    $routes->get('koefisien', 'Koefisien::index');
    $routes->post('koefisien/hitung', 'Koefisien::hitung');
    $routes->get('koefisien/hapus/(:num)', 'Koefisien::hapus/$1');

    // Pembagian Jam Mengajar PKL
    $routes->get('pembagianjam', 'Pembagianjam::index');
    $routes->post('pembagianjam/simpan', 'Pembagianjam::tambah');
    $routes->post('pembagianjam/tambah', 'Pembagianjam::tambah');
    $routes->post('pembagianjam/simpan_ajax', 'Pembagianjam::simpan_ajax');
    $routes->post('pembagianjam/simpan_bulk', 'Pembagianjam::simpan_bulk');
    $routes->get('pembagianjam/get_kelas_allocation/(:num)', 'Pembagianjam::get_kelas_allocation/$1');
    $routes->get('pembagianjam/get_kelas_allocation', 'Pembagianjam::get_kelas_allocation');
    $routes->get('pembagianjam/hapus/(:num)', 'Pembagianjam::hapus/$1');

    // Generate Pembimbing (Metode Koefisien)
    $routes->get('pembimbing', 'Pembimbing::index');
    $routes->post('pembimbing/generate', 'Pembimbing::generate');
    $routes->get('pembimbing/generate_semua', 'Pembimbing::generate_semua');
    $routes->get('pembimbing/reset/(:num)', 'Pembimbing::reset/$1');
    $routes->get('pembimbing/reset_semua', 'Pembimbing::reset_semua');

    // Generate Pembimbing (Metode Proporsional Kelas)
    $routes->get('pembimbingkelas', 'Pembimbingkelas::index');
    $routes->post('pembimbingkelas/generate', 'Pembimbingkelas::generate');
    $routes->get('pembimbingkelas/generate_semua', 'Pembimbingkelas::generate_semua');
    $routes->get('pembimbingkelas/reset/(:num)', 'Pembimbingkelas::reset/$1');
    $routes->get('pembimbingkelas/reset_semua', 'Pembimbingkelas::reset_semua');

    // Distribusi Siswa (Metode Koefisien)
    $routes->get('distribusimanual', 'Distribusimanual::index');
    $routes->get('distribusimanual/mapping/(:num)', 'Distribusimanual::mapping/$1');
    $routes->post('distribusimanual/simpan', 'Distribusimanual::simpan');
    $routes->get('distribusimanual/hapus/(:num)', 'Distribusimanual::hapus/$1');
    $routes->get('distribusimanual/reset', 'Distribusimanual::reset');

    // Distribusi Siswa (Metode Proporsional Kelas)
    $routes->get('distribusi_proporsional', 'Distribusi_proporsional::index');
    $routes->get('distribusi_proporsional/mapping/(:num)', 'Distribusi_proporsional::mapping/$1');
    $routes->post('distribusi_proporsional/simpan', 'Distribusi_proporsional::simpan');
    $routes->get('distribusi_proporsional/hapus/(:num)', 'Distribusi_proporsional::hapus/$1');
    $routes->get('distribusi_proporsional/reset', 'Distribusi_proporsional::reset');

    // Monitoring Aktivitas Siswa PKL (Presensi & Jurnal)
    $routes->get('monitoring', 'Monitoring::index');
    $routes->get('monitoring/absensi', 'Monitoring::absensi');
    $routes->get('monitoring_absensi', 'Monitoring::absensi');
    $routes->get('monitoring/jurnal', 'Monitoring::jurnal');
    $routes->get('monitoring_jurnal', 'Monitoring::jurnal');
    $routes->post('monitoring/jurnal/verifikasi/(:num)', 'Monitoring::jurnal_verifikasi/$1');
    $routes->post('monitoring/jurnal_verifikasi/(:num)', 'Monitoring::jurnal_verifikasi/$1');
    $routes->get('monitoring/jurnal/unlock/(:num)', 'Monitoring::jurnal_unlock/$1');
    $routes->get('monitoring/jurnal_unlock/(:num)', 'Monitoring::jurnal_unlock/$1');
    $routes->get('monitoring/jurnal/hapus/(:num)', 'Monitoring::jurnal_hapus/$1');
    $routes->get('monitoring/jurnal_hapus/(:num)', 'Monitoring::jurnal_hapus/$1');
    $routes->get('monitoring/cetak_absensi', 'Monitoring::cetak_absensi');
    $routes->get('monitoring/cetak_jurnal', 'Monitoring::cetak_jurnal');

    // Laporan & Cetak Pembimbing (Metode Koefisien)
    $routes->get('laporanpembimbing', 'LaporanPembimbing::index');
    $routes->get('laporanpembimbing/cetak', 'LaporanPembimbing::cetak');
    $routes->get('laporan-pembimbing', 'LaporanPembimbing::index');
    $routes->get('laporan-pembimbing/cetak', 'LaporanPembimbing::cetak');
    $routes->get('laporan/pembimbing', 'LaporanPembimbing::index');

    // Laporan & Cetak Pembimbing (Metode Proporsional Kelas)
    $routes->get('laporan_propor', 'Laporan_propor::index');
    $routes->get('laporan_propor/pembimbing', 'Laporan_propor::pembimbing');
    $routes->get('laporan_propor/rekap', 'Laporan_propor::rekap');
    $routes->get('laporan_propor/cetak', 'Laporan_propor::cetak');

    // Rekap PKL (Dapodik Ready)
    $routes->get('laporan/rekap', 'RekapPkl::index');
    $routes->get('rekappkl', 'RekapPkl::index');
    $routes->get('rekappkl/export_excel', 'RekapPkl::export_excel');

    // Pusat Unduhan
    $routes->get('pusatunduhan', 'Pusatunduhan::index');
    $routes->post('pusatunduhan/tambah', 'Pusatunduhan::tambah');
    $routes->get('pusatunduhan/hapus/(:num)', 'Pusatunduhan::hapus/$1');

    // Pengaturan
    $routes->get('pengaturan', 'Pengaturan::index');
    $routes->post('pengaturan/simpan', 'Pengaturan::simpan');
    $routes->post('pengaturan/reset_data', 'Pengaturan::reset_data');
    $routes->post('pengaturan/set_theme', 'Pengaturan::set_theme');
    $routes->get('pengaturan/set_theme', 'Pengaturan::set_theme');
    $routes->post('pengaturan/set_metode', 'Pengaturan::set_metode');
    $routes->get('pengaturan/set_metode', 'Pengaturan::set_metode');

    // Backup Database
    $routes->get('backup', 'Backup::index');
    $routes->get('backup/download', 'Backup::download');

    // Users / Operator Management
    $routes->get('users', 'Users::index');
    $routes->post('users/tambah', 'Users::tambah');
    $routes->post('users/edit/(:num)', 'Users::edit/$1');
    $routes->get('users/hapus/(:num)', 'Users::hapus/$1');
});

// Portal Siswa PKL (Login NISN)
$routes->group('siswa', ['filter' => 'auth:siswa'], function ($routes) {
    $routes->get('/', 'PortalSiswa::dashboard');
    $routes->get('dashboard', 'PortalSiswa::dashboard');
    $routes->get('absensi', 'PortalSiswa::absensi');
    $routes->post('absen_masuk', 'PortalSiswa::absen_masuk');
    $routes->post('absen_pulang', 'PortalSiswa::absen_pulang');
    $routes->get('jurnal', 'PortalSiswa::jurnal');
    $routes->post('jurnal/simpan', 'PortalSiswa::jurnal_simpan');
    $routes->post('jurnal_simpan', 'PortalSiswa::jurnal_simpan');
    $routes->post('jurnal/edit/(:num)', 'PortalSiswa::jurnal_edit/$1');
    $routes->post('jurnal_edit/(:num)', 'PortalSiswa::jurnal_edit/$1');
    $routes->get('jurnal/hapus/(:num)', 'PortalSiswa::jurnal_hapus/$1');
    $routes->get('jurnal_hapus/(:num)', 'PortalSiswa::jurnal_hapus/$1');
});

// Portal Guru Pembimbing & Kaprog PKL (Login NIP / Nama)
$routes->group('guru', ['filter' => 'auth:guru'], function ($routes) {
    $routes->get('/', 'PortalGuru::dashboard');
    $routes->get('dashboard', 'PortalGuru::dashboard');
    $routes->get('siswa', 'PortalGuru::siswa');
    $routes->get('absensi', 'PortalGuru::absensi');
    $routes->get('jurnal', 'PortalGuru::jurnal');
    $routes->post('jurnal/verifikasi/(:num)', 'PortalGuru::jurnal_verifikasi/$1');
    $routes->post('jurnal_verifikasi/(:num)', 'PortalGuru::jurnal_verifikasi/$1');
    $routes->get('kaprog', 'PortalGuru::kaprog');
});
