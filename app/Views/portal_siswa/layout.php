<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= esc($title ?? 'Portal Siswa PKL') ?> &bull; PEKAEL</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/modern-custom.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/theme-dark.css') ?>" rel="stylesheet">

    <style>
        :root {
            --portal-primary: #4f46e5;
            --portal-dark: #1e1b4b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .portal-navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            height: 70px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .portal-nav-pill {
            padding: 8px 16px;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            text-decoration: none !important;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .portal-nav-pill:hover,
        .portal-nav-pill.active {
            background-color: #eef2ff;
            color: #4f46e5;
        }

        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            height: 64px;
            z-index: 1030;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 767.98px) {
            .bottom-nav {
                display: flex;
            }
            body {
                padding-bottom: 74px;
            }
        }

        .bottom-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #64748b;
            text-decoration: none !important;
            font-size: 0.72rem;
            font-weight: 600;
            gap: 3px;
        }

        .bottom-nav-item.active {
            color: #4f46e5;
        }

        .bottom-nav-item i {
            font-size: 1.15rem;
        }
    </style>
</head>
<body>

    <!-- Top Navigation Bar -->
    <nav class="portal-navbar px-3 px-md-4 d-flex align-items-center justify-content-between">
        
        <!-- Left: School Identity -->
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm mr-2.5" style="width: 40px; height: 40px; font-size: 1.1rem; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div>
                <span class="font-weight-800 text-gray-900 d-block" style="font-size: 0.95rem; line-height: 1.2;">PEKAEL SISWA</span>
                <span class="text-xs text-muted font-weight-500">Portal Monitoring &amp; Presensi PKL</span>
            </div>
        </div>

        <!-- Center: Desktop Menu Links -->
        <div class="d-none d-md-flex align-items-center" style="gap: 8px;">
            <a href="<?= site_url('siswa/dashboard') ?>" class="portal-nav-pill <?= uri_string() === 'siswa/dashboard' ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Beranda
            </a>
            <a href="<?= site_url('siswa/absensi') ?>" class="portal-nav-pill <?= uri_string() === 'siswa/absensi' ? 'active' : '' ?>">
                <i class="fas fa-camera"></i> Absensi GPS
            </a>
            <a href="<?= site_url('siswa/jurnal') ?>" class="portal-nav-pill <?= uri_string() === 'siswa/jurnal' ? 'active' : '' ?>">
                <i class="fas fa-book-open"></i> Jurnal Harian
            </a>
        </div>

        <!-- Right: Student Profile & Logout -->
        <div class="d-flex align-items-center" style="gap: 10px;">
            <div class="text-right d-none d-sm-block">
                <span class="font-weight-bold text-gray-900 d-block" style="font-size: 0.85rem; line-height: 1.2;">
                    <?= esc(session()->get('nama_lengkap')) ?>
                </span>
                <span class="badge badge-light border text-muted font-weight-normal text-xs">
                    NISN: <?= esc(session()->get('nisn')) ?>
                </span>
            </div>
            <a href="<?= site_url('auth/logout') ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 font-weight-bold d-flex align-items-center" title="Keluar dari akun siswa">
                <i class="fas fa-sign-out-alt mr-1"></i> <span class="d-none d-sm-inline">Keluar</span>
            </a>
        </div>

    </nav>

    <!-- Main Container -->
    <div class="container-fluid px-3 px-md-4 py-4 flex-grow-1" style="max-width: 1200px;">
        
        <!-- Flash Alerts -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-xl py-3 px-3 mb-4" role="alert" style="background-color: #ecfdf5; color: #065f46; border-left: 5px solid #10b981 !important;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-lg mr-2.5 text-success"></i>
                    <div class="font-weight-600 text-sm flex-grow-1"><?= session()->getFlashdata('success') ?></div>
                </div>
                <button type="button" class="close text-success" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-xl py-3 px-3 mb-4" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 5px solid #ef4444 !important;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-lg mr-2.5 text-danger"></i>
                    <div class="font-weight-600 text-sm flex-grow-1"><?= session()->getFlashdata('error') ?></div>
                </div>
                <button type="button" class="close text-danger" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>

    </div>

    <!-- Mobile Bottom Navigation Bar -->
    <div class="bottom-nav">
        <a href="<?= site_url('siswa/dashboard') ?>" class="bottom-nav-item <?= uri_string() === 'siswa/dashboard' ? 'active' : '' ?>">
            <i class="fas fa-home"></i>
            <span>Beranda</span>
        </a>
        <a href="<?= site_url('siswa/absensi') ?>" class="bottom-nav-item <?= uri_string() === 'siswa/absensi' ? 'active' : '' ?>">
            <i class="fas fa-camera"></i>
            <span>Absen GPS</span>
        </a>
        <a href="<?= site_url('siswa/jurnal') ?>" class="bottom-nav-item <?= uri_string() === 'siswa/jurnal' ? 'active' : '' ?>">
            <i class="fas fa-book-open"></i>
            <span>Jurnal</span>
        </a>
        <a href="<?= site_url('auth/logout') ?>" class="bottom-nav-item text-danger" onclick="return confirm('Keluar dari Portal Siswa?');">
            <i class="fas fa-sign-out-alt"></i>
            <span>Keluar</span>
        </a>
    </div>

    <script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
