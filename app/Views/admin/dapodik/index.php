<!-- Page Heading -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Integrasi Dapodik (Read-Only)</h1>
        <p class="text-muted text-sm mb-0">Penarikan otomatis data referensi Dapodik lokal secara aman tanpa mengubah satu pun data di Dapodik.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= site_url('dapodik/tarik_semua') ?>" class="btn btn-success shadow-sm btn-tarik-dapo px-3 py-2 font-weight-bold" onclick="return confirm('Apakah Anda yakin ingin menarik seluruh data dari Dapodik sekaligus? (Proses ini aman dan bersifat READ-ONLY)');">
            <i class="fas fa-cloud-download-alt mr-2"></i> Tarik Seluruh Data Dapodik
        </a>
    </div>
</div>

<!-- Alert Notifikasi Session -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle fa-2x mr-3 text-success"></i>
            <div>
                <strong>Berhasil!</strong><br>
                <?= session()->getFlashdata('success') ?>
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x mr-3 text-danger"></i>
            <div>
                <strong>Perhatian:</strong><br>
                <?= session()->getFlashdata('error') ?>
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Dapodik Connection Status Card -->
<div class="card shadow mb-4 border-0">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-2">
                    <?php if ($dapo_status['success'] ?? false): ?>
                        <span class="badge badge-success px-3 py-2 font-weight-bold d-inline-flex align-items-center mr-3" style="font-size: 0.85rem; border-radius: 30px;">
                            <span class="d-inline-block rounded-circle bg-white mr-2" style="width: 8px; height: 8px; animation: pulse 1.5s infinite;"></span>
                            TERHUBUNG KE DAPODIK LOKAL
                        </span>
                        <span class="badge badge-light border text-secondary px-2 py-1 text-xs">PostgreSQL 127.0.0.1:54532</span>
                    <?php else: ?>
                        <span class="badge badge-warning px-3 py-2 font-weight-bold d-inline-flex align-items-center mr-3" style="font-size: 0.85rem; border-radius: 30px;">
                            <i class="fas fa-exclamation-circle mr-2"></i> BELUM TERHUBUNG KE DAPODIK
                        </span>
                    <?php endif; ?>
                    <span class="badge badge-info px-2 py-1 text-xs"><i class="fas fa-lock mr-1"></i> Read-Only Mode Enforced</span>
                </div>
                <h4 class="font-weight-bold text-gray-900 mb-1">
                    <?= $dapo_status['success'] ? ($dapo_status['details']['nama_sekolah'] ?? 'Dapodik Sekolah') : 'Status Koneksi Database Dapodik' ?>
                </h4>
                <p class="text-muted text-sm mb-0">
                    <?= $dapo_status['message'] ?? 'Memeriksa jalur database lokal Dapodik...' ?>
                    <?php if (!empty($dapo_status['details']['npsn'])): ?>
                        | <strong>NPSN:</strong> <?= esc($dapo_status['details']['npsn']) ?>
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                <a href="<?= site_url('dapodik') ?>" class="btn btn-outline-primary btn-sm px-3 shadow-sm">
                    <i class="fas fa-sync mr-1"></i> Cek Ulang Status Koneksi
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Info Banner Skema Integrasi -->
<div class="card bg-gradient-primary text-white shadow mb-4 border-0" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-9">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-shield-alt fa-2x mr-3 text-warning"></i>
                    <h5 class="font-weight-bold mb-0">Jaminan Keamanan & Integritas Data (Zero Mutation)</h5>
                </div>
                <p class="text-white-50 text-sm mb-0">
                    Alur: <strong>DATA DAPODIK &rarr; Tarik (Read-Only) &rarr; Database PEKAEL</strong>. Modul penarikan hanya menjalankan kueri <code>SELECT</code> dengan transaksi read-only. Database Dapodik sama sekali tidak pernah diubah atau ditulis ulang. Selain itu, Anda tetap dapat menambah, mengedit, atau mengimpor data via Excel secara manual kapan saja.
                </p>
            </div>
            <div class="col-md-3 text-md-right mt-3 mt-md-0">
                <div class="bg-white rounded p-2 text-center text-dark shadow-sm">
                    <div class="text-xs text-muted text-uppercase font-weight-bold">Tahun Ajaran Aktif</div>
                    <div class="font-weight-bold text-primary" style="font-size: 1.1rem;"><?= esc($tahun->tahun ?? '-') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sync Cards Grid -->
<div class="row">
    <!-- 1. Profil Sekolah -->
    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card shadow-sm h-100 border-left-primary">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-circle p-3 bg-primary text-white">
                        <i class="fas fa-school fa-lg"></i>
                    </div>
                    <span class="badge badge-primary px-2 py-1">Master Profil</span>
                </div>
                <h5 class="font-weight-bold text-gray-900 mb-1">1. Profil Sekolah</h5>
                <p class="text-muted text-xs mb-3">Menarik Nama Sekolah, NPSN, Alamat lengkap, Nama Kepala Sekolah & NIP dari tabel profil Dapodik.</p>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                    <span class="text-xs text-muted">Status: Read-Only Sync</span>
                    <a href="<?= site_url('dapodik/tarik_sekolah') ?>" class="btn btn-sm btn-primary px-3 shadow-sm" onclick="return confirm('Tarik pembaruan data profil sekolah dari Dapodik?');">
                        <i class="fas fa-download mr-1"></i> Tarik Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Data Jurusan -->
    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card shadow-sm h-100 border-left-info">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-circle p-3 bg-info text-white">
                        <i class="fas fa-network-wired fa-lg"></i>
                    </div>
                    <span class="badge badge-light border text-info px-2 py-1 font-weight-bold"><?= $pekael_stats['total_jurusan'] ?> di PEKAEL</span>
                </div>
                <h5 class="font-weight-bold text-gray-900 mb-1">2. Data Jurusan</h5>
                <p class="text-muted text-xs mb-3">Menarik daftar kompetensi keahlian / jurusan SMK yang terdaftar aktif di Dapodik sekolah.</p>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                    <span class="text-xs text-muted">Dapodik: <?= $dapo_status['counts']['jurusan'] ?? '-' ?> Jurusan</span>
                    <a href="<?= site_url('dapodik/tarik_jurusan') ?>" class="btn btn-sm btn-info px-3 shadow-sm" onclick="return confirm('Tarik data jurusan dari Dapodik?');">
                        <i class="fas fa-download mr-1"></i> Tarik Jurusan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Data Kelas / Rombel -->
    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card shadow-sm h-100 border-left-success">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-circle p-3 bg-success text-white">
                        <i class="fas fa-chalkboard fa-lg"></i>
                    </div>
                    <span class="badge badge-light border text-success px-2 py-1 font-weight-bold"><?= $pekael_stats['total_kelas'] ?> di PEKAEL</span>
                </div>
                <h5 class="font-weight-bold text-gray-900 mb-1">3. Data Kelas (Tingkat XII)</h5>
                <p class="text-muted text-xs mb-3">Menarik rombongan belajar tingkat XII/XIII reguler yang menjadi sasaran pelaksanaan PKL SMK.</p>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                    <span class="text-xs text-muted">Dapodik: <?= $dapo_status['counts']['kelas'] ?? '-' ?> Rombel</span>
                    <a href="<?= site_url('dapodik/tarik_kelas') ?>" class="btn btn-sm btn-success px-3 shadow-sm" onclick="return confirm('Tarik data kelas tingkat XII dari Dapodik?');">
                        <i class="fas fa-download mr-1"></i> Tarik Kelas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Data Mitra DUDI & MoU -->
    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card shadow-sm h-100 border-left-warning">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-circle p-3 bg-warning text-white">
                        <i class="fas fa-building fa-lg"></i>
                    </div>
                    <span class="badge badge-light border text-warning px-2 py-1 font-weight-bold"><?= $pekael_stats['total_dudi'] ?> di PEKAEL</span>
                </div>
                <h5 class="font-weight-bold text-gray-900 mb-1">4. Mitra DUDI & MoU</h5>
                <p class="text-muted text-xs mb-3">Menarik nama mitra dunia usaha/industri beserta nomor MoU & judul PKS yang telah diinput di Dapodik.</p>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                    <span class="text-xs text-muted">Dapodik: <?= $dapo_status['counts']['dudi'] ?? '-' ?> DUDI</span>
                    <a href="<?= site_url('dapodik/tarik_dudi') ?>" class="btn btn-sm btn-warning px-3 text-white shadow-sm" onclick="return confirm('Tarik data DUDI & MoU dari Dapodik?');">
                        <i class="fas fa-download mr-1"></i> Tarik DUDI
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Data Guru / PTK -->
    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card shadow-sm h-100 border-left-secondary">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-circle p-3 bg-secondary text-white">
                        <i class="fas fa-user-tie fa-lg"></i>
                    </div>
                    <span class="badge badge-light border text-secondary px-2 py-1 font-weight-bold"><?= $pekael_stats['total_guru'] ?> di PEKAEL</span>
                </div>
                <h5 class="font-weight-bold text-gray-900 mb-1">5. Data Guru / PTK</h5>
                <p class="text-muted text-xs mb-3">Menarik nama guru dan NIP aktif dari Dapodik untuk dipetakan sebagai calon pembimbing PKL.</p>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                    <span class="text-xs text-muted">Dapodik: <?= $dapo_status['counts']['guru'] ?? '-' ?> Guru</span>
                    <a href="<?= site_url('dapodik/tarik_guru') ?>" class="btn btn-sm btn-secondary px-3 shadow-sm" onclick="return confirm('Tarik data guru dari Dapodik?');">
                        <i class="fas fa-download mr-1"></i> Tarik Guru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. Data Siswa Peserta PKL -->
    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card shadow-sm h-100 border-left-danger">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-circle p-3 bg-danger text-white">
                        <i class="fas fa-user-graduate fa-lg"></i>
                    </div>
                    <span class="badge badge-light border text-danger px-2 py-1 font-weight-bold"><?= $pekael_stats['total_siswa'] ?> Siswa Aktif</span>
                </div>
                <h5 class="font-weight-bold text-gray-900 mb-1">6. Data Siswa PKL</h5>
                <p class="text-muted text-xs mb-3">Menarik siswa rombel sasaran PKL beserta NISN, jenis kelamin, dan relasi rombel aktif.</p>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                    <span class="text-xs text-muted">Dapodik: <?= $dapo_status['counts']['siswa'] ?? '-' ?> Siswa</span>
                    <a href="<?= site_url('dapodik/tarik_siswa') ?>" class="btn btn-sm btn-danger px-3 shadow-sm" onclick="return confirm('Tarik data siswa peserta PKL dari Dapodik?');">
                        <i class="fas fa-download mr-1"></i> Tarik Siswa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
