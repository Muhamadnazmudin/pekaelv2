<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login Operator - PEKAEL JJM PKL</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/modern-custom.css') ?>" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .login-card {
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 1.5rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35) !important;
            backdrop-filter: blur(12px);
            background: #ffffff;
            overflow: hidden;
            max-width: 480px;
            width: 100%;
        }
        .form-control-user {
            border-radius: 0.75rem !important;
            padding: 1.4rem 1.25rem !important;
            font-size: 0.95rem !important;
            border: 1.5px solid #e2e8f0;
        }
        .form-control-user:focus {
            border-color: #4338ca;
            box-shadow: 0 0 0 4px rgba(67, 56, 202, 0.12);
        }
        .btn-user {
            border-radius: 0.75rem !important;
            padding: 0.85rem !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
        }
    </style>
</head>

<body>
    <div class="container px-3">
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-center">
                <div class="login-card p-4 p-sm-5 my-5">
                    
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow mb-3" style="width: 64px; height: 64px; font-size: 1.75rem;">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="font-weight-bold text-gray-900 mb-1" style="letter-spacing: -0.02em;">PEKAEL</h3>
                        <p class="text-muted text-sm mb-0">Sistem Perhitungan JJM PKL & Integrasi Dapodik</p>
                        <?php if (!empty($sekolah->nama_sekolah)): ?>
                        <div class="badge badge-light border text-muted mt-2 px-3 py-1 font-weight-normal">
                            <?= esc($sekolah->nama_sekolah) ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center rounded-lg mb-4 text-sm" style="background-color: #fef2f2; color: #991b1b;">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center rounded-lg mb-4 text-sm" style="background-color: #ecfdf5; color: #065f46;">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                    <?php endif; ?>

                    <form class="user" action="<?= site_url('auth/process') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="form-group mb-3">
                            <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Username Operator</label>
                            <div class="input-group">
                                <input type="text" name="username" class="form-control form-control-user" placeholder="Masukkan username..." required autofocus value="<?= old('username') ?>">
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Password</label>
                            <input type="password" name="password" class="form-control form-control-user" placeholder="Masukkan password..." required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-user btn-block shadow">
                            <i class="fas fa-sign-in-alt mr-2"></i> Masuk ke Sistem
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <a class="small font-weight-bold text-primary" href="<?= site_url('cek') ?>">
                            <i class="fas fa-search mr-1"></i> Portal Siswa: Cek Status Penempatan PKL
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
