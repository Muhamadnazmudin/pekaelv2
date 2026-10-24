<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= esc($title) ?> &bull; <?= esc($sekolah->nama_sekolah ?? 'PEKAEL') ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/modern-custom.css') ?>" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #312e81 0%, #1e1b4b 40%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: #1e293b;
        }

        .portal-card {
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(25px);
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.45);
            max-width: 540px;
            width: 100%;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .brand-header-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
            color: #ffffff;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.5);
        }

        .nisn-input-box {
            border-radius: 14px !important;
            padding: 1.1rem 1.25rem !important;
            font-size: 1.1rem !important;
            letter-spacing: 0.08em;
            border: 2px solid #e2e8f0;
            transition: all 0.25s ease;
        }

        .nisn-input-box:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15) !important;
        }

        .portal-nav-btn {
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 0.82rem;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none !important;
        }

        .portal-nav-btn:hover {
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="portal-card p-4 p-sm-5">
    
    <!-- Header Section -->
    <div class="text-center mb-4">
        <div class="brand-header-icon mb-3">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h3 class="font-weight-800 text-gray-900 mb-1" style="letter-spacing: -0.02em;">Portal Cek Penempatan PKL</h3>
        <p class="text-muted text-sm font-weight-500 mb-0"><?= esc($sekolah->nama_sekolah ?? 'SMK Indonesia') ?></p>
        
        <div class="d-inline-flex align-items-center mt-2 px-3 py-1 bg-light rounded-pill border text-xs text-secondary font-weight-bold">
            <i class="fas fa-school text-primary mr-1.5"></i> NPSN: <?= esc($sekolah->npsn ?? '-') ?>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-xl py-3 px-3 mb-4" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle fa-lg mr-2.5 text-danger"></i>
                <div class="text-xs font-weight-600 flex-grow-1"><?= session()->getFlashdata('error') ?></div>
            </div>
            <button type="button" class="close text-danger" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Search Form -->
    <form action="<?= site_url('cek/hasil') ?>" method="POST" class="mb-4">
        <?= csrf_field() ?>
        
        <div class="form-group mb-3">
            <label class="font-weight-bold text-gray-700 text-xs text-uppercase mb-2 d-flex justify-content-between align-items-center" style="letter-spacing: 0.05em;">
                <span>Nomor Induk Siswa Nasional (NISN)</span>
                <span class="text-muted font-weight-normal text-xs">10 Digit Angka</span>
            </label>
            <div class="input-group">
                <input type="text" name="nisn" class="form-control nisn-input-box font-weight-bold text-center text-gray-900" placeholder="Masukkan 10 digit NISN..." required autofocus maxlength="15" autocomplete="off" inputmode="numeric">
            </div>
            <small class="form-text text-muted mt-2 text-center">
                <i class="fas fa-info-circle mr-1 text-primary"></i> Masukkan NISN resmi Anda yang terdaftar pada sistem Dapodik sekolah.
            </small>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-lg font-weight-bold shadow-sm py-3 mt-4" style="border-radius: 14px; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none;">
            <i class="fas fa-search mr-2"></i> Periksa Status Penempatan PKL
        </button>
    </form>

    <!-- Quick Access: Siswa & Guru Login Card -->
    <div class="p-3 rounded-xl bg-light border mb-3">
        <div class="text-xs font-weight-bold text-uppercase text-muted mb-2 text-center" style="letter-spacing: 0.05em;">
            Akses Login Siswa &amp; Guru
        </div>
        <div class="row g-2">
            <div class="col-6 pr-1">
                <a href="<?= site_url('login') ?>" class="btn btn-outline-primary btn-block portal-nav-btn text-left d-flex align-items-center">
                    <i class="fas fa-user-graduate mr-2 text-primary"></i>
                    <div>
                        <div class="font-weight-bold text-xs" style="line-height: 1.1;">Login Siswa</div>
                        <div class="text-xs text-muted font-weight-normal" style="font-size: 0.65rem;">Pakai NISN</div>
                    </div>
                </a>
            </div>
            <div class="col-6 pl-1">
                <a href="<?= site_url('login') ?>" class="btn btn-outline-secondary btn-block portal-nav-btn text-left d-flex align-items-center">
                    <i class="fas fa-chalkboard-teacher mr-2 text-success"></i>
                    <div>
                        <div class="font-weight-bold text-xs" style="line-height: 1.1;">Login Guru</div>
                        <div class="text-xs text-muted font-weight-normal" style="font-size: 0.65rem;">Pakai NIP</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer Operator Link -->
    <div class="text-center pt-2">
        <a href="<?= site_url('login') ?>" class="text-xs text-muted font-weight-bold text-decoration-none">
            <i class="fas fa-shield-alt mr-1"></i> Portal Operator / Administrator Pokja
        </a>
    </div>

</div>

<script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
