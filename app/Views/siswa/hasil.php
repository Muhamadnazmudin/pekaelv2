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

        .result-card {
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(25px);
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.45);
            max-width: 680px;
            width: 100%;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .header-banner {
            background: linear-gradient(135deg, #4f46e5 0%, #312e81 100%);
            color: #fff;
            padding: 32px 36px;
            position: relative;
        }

        .status-badge-hero {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 4px 12px;
            display: inline-flex;
            align-items: center;
        }

        .info-panel {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            transition: all 0.2s ease;
        }

        .info-panel:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .result-card {
                box-shadow: none;
                border: none;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="result-card">
    
    <!-- Hero Header -->
    <div class="header-banner">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <span class="status-badge-hero mb-2">
                    <i class="fas fa-check-circle mr-1.5 text-warning"></i> SURAT KETERANGAN PENEMPATAN PKL
                </span>
                <h3 class="font-weight-800 text-white mb-1" style="letter-spacing: -0.02em; font-size: 1.6rem;">
                    <?= esc($siswa->nama) ?>
                </h3>
                <div class="text-white-50 text-sm font-weight-500">
                    <span>NISN: <strong class="text-white"><?= esc($siswa->nisn) ?></strong></span>
                    <span class="mx-2">&bull;</span>
                    <span>Kelas: <strong class="text-white"><?= esc($siswa->nama_kelas ?? '-') ?></strong></span>
                    <?php if (!empty($siswa->nama_jurusan)): ?>
                        <span class="mx-2">&bull;</span>
                        <span><?= esc($siswa->nama_jurusan) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="d-none d-sm-block text-right">
                <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center shadow" style="width: 58px; height: 58px; font-size: 1.6rem;">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Body Information -->
    <div class="p-4 p-md-5">
        
        <!-- Mitra DUDI -->
        <div class="info-panel mb-3">
            <div class="text-xs text-uppercase font-weight-bold text-muted mb-2 d-flex justify-content-between align-items-center" style="letter-spacing: 0.05em;">
                <span><i class="fas fa-industry text-primary mr-1"></i> Tempat Praktik Kerja Lapangan (Mitra DUDI)</span>
                <?php if (!empty($siswa->nama_dudi)): ?>
                    <span class="badge badge-success px-2 py-1">Telah Ditempatkan</span>
                <?php else: ?>
                    <span class="badge badge-warning px-2 py-1">Menunggu Penempatan</span>
                <?php endif; ?>
            </div>

            <?php if (!empty($siswa->nama_dudi)): ?>
                <h4 class="font-weight-bold text-gray-900 mb-1" style="font-size: 1.25rem;">
                    <?= esc($siswa->nama_dudi) ?>
                </h4>
                <p class="text-muted text-sm mb-3">
                    <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                    <?= esc($siswa->alamat_dudi ?: 'Alamat mitra industri sedang dilengkapi oleh pihak sekolah.') ?>
                </p>
                
                <?php if (!empty($siswa->nama_pic) || !empty($siswa->no_hp_pic)): ?>
                    <div class="d-flex align-items-center flex-wrap pt-2.5 border-top text-xs text-secondary" style="gap: 12px;">
                        <?php if (!empty($siswa->nama_pic)): ?>
                            <span><strong>Pembimbing Industri (PIC):</strong> <?= esc($siswa->nama_pic) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($siswa->no_hp_pic)): ?>
                            <span><strong>Kontak/WA:</strong> <?= esc($siswa->no_hp_pic) ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="p-3 bg-warning-light rounded-lg border border-warning text-warning-dark text-sm">
                    <i class="fas fa-clock mr-1.5"></i> Penempatan mitra industri Anda sedang diproses oleh Tim Pokja PKL. Silakan cek secara berkala.
                </div>
            <?php endif; ?>
        </div>

        <!-- Guru Pembimbing Sekolah -->
        <div class="info-panel mb-4">
            <div class="text-xs text-uppercase font-weight-bold text-muted mb-2" style="letter-spacing: 0.05em;">
                <i class="fas fa-user-tie text-primary mr-1"></i> Guru Pembimbing Lapangan Sekolah
            </div>

            <?php if (!empty($siswa->nama_pembimbing)): ?>
                <h5 class="font-weight-bold text-gray-900 mb-1" style="font-size: 1.1rem;">
                    <?= esc($siswa->nama_pembimbing) ?>
                </h5>
                <p class="text-muted text-xs mb-0">
                    NIP: <strong><?= esc($siswa->nip_pembimbing ?: '-') ?></strong>
                </p>
            <?php else: ?>
                <div class="p-3 bg-light rounded-lg border text-muted text-sm">
                    <i class="fas fa-info-circle mr-1.5 text-primary"></i> Guru pembimbing sekolah sedang dalam proses alokasi jam mengajar mingguan (JJM) PKL.
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Login to Portal Siswa Alert -->
        <div class="p-3 rounded-xl bg-primary-light border border-primary text-primary mb-4 no-print d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
            <div>
                <div class="font-weight-bold text-sm"><i class="fas fa-mobile-alt mr-1"></i> Sudah Bisa Absensi &amp; Jurnal Harian?</div>
                <div class="text-xs text-muted">Masuk ke Portal Siswa menggunakan <strong>NISN</strong> Anda sebagai username &amp; password.</div>
            </div>
            <a href="<?= site_url('login') ?>" class="btn btn-sm btn-primary font-weight-bold px-3 py-2 rounded-pill shadow-sm">
                <i class="fas fa-sign-in-alt mr-1"></i> Buka Portal Siswa
            </a>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center pt-3 border-top no-print" style="gap: 10px;">
            <a href="<?= site_url('cek') ?>" class="btn btn-outline-secondary btn-sm font-weight-bold px-3 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-1"></i> Cek NISN Lain
            </a>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <button onclick="window.print()" class="btn btn-primary btn-sm font-weight-bold px-3 py-2 rounded-lg shadow-sm">
                    <i class="fas fa-print mr-1"></i> Cetak Bukti Penempatan
                </button>
            </div>
        </div>

    </div>

</div>

<script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
