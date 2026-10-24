<!-- Page Heading & Quick Actions -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold" style="letter-spacing: -0.02em;">Dashboard PKL & JJM Dapodik</h1>
        <p class="text-muted text-sm mb-0">Selamat datang di sistem monitoring dan penghitungan Jam Mengajar Mingguan (JJM) PKL SMK.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= site_url('dapodik') ?>" class="btn btn-success shadow-sm btn-tarik-dapo mr-2" data-title="Buka Sinkronisasi Dapodik">
            <i class="fas fa-sync-alt mr-1"></i> Tarik Data Dapodik (Read-Only)
        </a>
        <a href="<?= site_url('rekappkl/export_excel') ?>" class="btn btn-primary shadow-sm">
            <i class="fas fa-file-excel mr-1"></i> Ekspor Rekap Dapodik
        </a>
    </div>
</div>

<!-- Workflow Stepper Guide for School Operators -->
<div class="workflow-step-card mb-4">
    <div class="row align-items-center">
        <div class="col-lg-4 mb-3 mb-lg-0">
            <span class="badge badge-light text-primary px-3 py-1 font-weight-bold mb-2">PANDUAN OPERATOR</span>
            <h4 class="font-weight-bold mb-2 text-white">Alur Perhitungan JJM PKL</h4>
            <p class="mb-0 text-white-50 text-sm">Ikuti 5 tahapan berurutan ini agar alokasi JJM PKL guru presisi saat diinputkan ke aplikasi Dapodik.</p>
        </div>
        <div class="col-lg-8">
            <div class="row g-2">
                <div class="col-sm-6 col-md-4 mb-2">
                    <a href="<?= site_url('dapodik') ?>" class="stepper-item">
                        <span class="stepper-badge">1</span>
                        <div>
                            <div class="font-weight-bold text-xs text-uppercase" style="letter-spacing: 0.05em;">Tarik / Master</div>
                            <div class="text-xs text-white-50">Data Siswa, Kelas & DUDI</div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4 mb-2">
                    <a href="<?= site_url('mappingdudi') ?>" class="stepper-item">
                        <span class="stepper-badge">2</span>
                        <div>
                            <div class="font-weight-bold text-xs text-uppercase" style="letter-spacing: 0.05em;">Mapping DUDI</div>
                            <div class="text-xs text-white-50">Tempatkan siswa ke DUDI</div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4 mb-2">
                    <a href="<?= site_url('koefisien') ?>" class="stepper-item">
                        <span class="stepper-badge">3</span>
                        <div>
                            <div class="font-weight-bold text-xs text-uppercase" style="letter-spacing: 0.05em;">Koefisien PKL</div>
                            <div class="text-xs text-white-50">Hitung rumus (Rombel*JP)/Siswa</div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4 mb-2">
                    <a href="<?= site_url('pembagianjam') ?>" class="stepper-item">
                        <span class="stepper-badge">4</span>
                        <div>
                            <div class="font-weight-bold text-xs text-uppercase" style="letter-spacing: 0.05em;">Pembagian Jam</div>
                            <div class="text-xs text-white-50">Bagi 46 JP guru per rombel</div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4 mb-2">
                    <a href="<?= site_url('pembimbing') ?>" class="stepper-item">
                        <span class="stepper-badge">5</span>
                        <div>
                            <div class="font-weight-bold text-xs text-uppercase" style="letter-spacing: 0.05em;">Generate Kuota</div>
                            <div class="text-xs text-white-50">Hitung kuota siswa per guru</div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4 mb-2">
                    <a href="<?= site_url('rekappkl') ?>" class="stepper-item">
                        <span class="stepper-badge">6</span>
                        <div>
                            <div class="font-weight-bold text-xs text-uppercase" style="letter-spacing: 0.05em;">Rekap Dapodik</div>
                            <div class="text-xs text-white-50">Cetak & siap input ke Dapodik</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Primary Metric KPI Cards -->
<div class="row mb-4">

    <!-- Total Siswa PKL -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-widget h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-xs font-weight-bold text-uppercase text-primary" style="letter-spacing: 0.05em;">Total Siswa PKL</span>
                    <div class="stat-icon bg-primary-light text-primary">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div class="stat-number text-dark mb-1"><?= number_format($total_siswa) ?></div>
                <div class="text-xs text-muted">
                    <i class="fas fa-check-circle text-success mr-1"></i> Siswa Tingkat PKL aktif
                </div>
            </div>
        </div>
    </div>

    <!-- Rombel PKL -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-widget h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-xs font-weight-bold text-uppercase text-info" style="letter-spacing: 0.05em;">Rombel PKL Aktif</span>
                    <div class="stat-icon bg-info-light text-info">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
                <div class="stat-number text-dark mb-1"><?= number_format($total_rombel) ?> <small class="text-muted font-weight-normal" style="font-size: 1rem;">/ <?= $total_rombel_semua ?> Rombel</small></div>
                <div class="text-xs text-muted">
                    <i class="fas fa-layer-group text-info mr-1"></i> Kelas berstatus PKL = 'Ya'
                </div>
            </div>
        </div>
    </div>

    <!-- Koefisien PKL -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-widget h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-xs font-weight-bold text-uppercase text-warning" style="letter-spacing: 0.05em;">Koefisien PKL</span>
                    <div class="stat-icon bg-warning-light text-warning">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
                <div class="stat-number text-dark mb-1"><?= number_format($koefisien, 6) ?></div>
                <div class="text-xs text-muted">
                    <span class="badge badge-soft-warning font-weight-bold"><?= $jp_wajib ?> JP Wajib</span> per rombel
                </div>
            </div>
        </div>
    </div>

    <!-- Total DUDI Aktif -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-widget h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-xs font-weight-bold text-uppercase text-success" style="letter-spacing: 0.05em;">Mitra DUDI & MoU</span>
                    <div class="stat-icon bg-success-light text-success">
                        <i class="fas fa-handshake"></i>
                    </div>
                </div>
                <div class="stat-number text-dark mb-1"><?= number_format($total_dudi) ?></div>
                <div class="text-xs text-muted">
                    <i class="fas fa-file-contract text-success mr-1"></i> Kerjasama MoU Aktif
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Status Penempatan & Progress Overview -->
<div class="row mb-4">

    <!-- Progress Penempatan DUDI -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-building mr-2"></i> Status Penempatan DUDI
                </h6>
                <a href="<?= site_url('mappingdudi') ?>" class="btn btn-sm btn-outline-primary py-1 px-2 text-xs">
                    Kelola Mapping <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body">
                <?php
                $sudahDudi = max(0, $total_siswa - $belum_dudi);
                $persenDudi = ($total_siswa > 0) ? round(($sudahDudi / $total_siswa) * 100, 1) : 0;
                ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="font-weight-bold text-sm">Siswa Terpetakan ke DUDI</span>
                    <span class="font-weight-bold text-sm <?= ($persenDudi >= 100) ? 'text-success' : 'text-primary' ?>"><?= $sudahDudi ?> / <?= $total_siswa ?> (<?= $persenDudi ?>%)</span>
                </div>
                <div class="progress mb-4" style="height: 12px; border-radius: 6px;">
                    <div class="progress-bar <?= ($persenDudi >= 100) ? 'bg-success' : 'bg-primary' ?> progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $persenDudi ?>%"></div>
                </div>

                <div class="row text-center pt-2 border-top">
                    <div class="col-6 border-right">
                        <div class="text-xs text-muted text-uppercase font-weight-bold">Sudah Ada DUDI</div>
                        <div class="h5 font-weight-bold text-success mb-0"><?= $sudahDudi ?> Siswa</div>
                    </div>
                    <div class="col-6">
                        <div class="text-xs text-muted text-uppercase font-weight-bold">Belum Ada DUDI</div>
                        <div class="h5 font-weight-bold text-danger mb-0"><?= $belum_dudi ?> Siswa</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Alokasi Pembimbing -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-user-check mr-2"></i> Status Distribusi Pembimbing (Siap Dapodik)
                </h6>
                <a href="<?= site_url('distribusimanual') ?>" class="btn btn-sm btn-outline-success py-1 px-2 text-xs">
                    Kelola Distribusi <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body">
                <?php
                $sudahPembimbing = max(0, $total_siswa - $belum_pembimbing);
                $persenPembimbing = ($total_siswa > 0) ? round(($sudahPembimbing / $total_siswa) * 100, 1) : 0;
                ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="font-weight-bold text-sm">Siswa Terdistribusi ke Pembimbing</span>
                    <span class="font-weight-bold text-sm <?= ($persenPembimbing >= 100) ? 'text-success' : 'text-warning' ?>"><?= $sudahPembimbing ?> / <?= $total_siswa ?> (<?= $persenPembimbing ?>%)</span>
                </div>
                <div class="progress mb-4" style="height: 12px; border-radius: 6px;">
                    <div class="progress-bar <?= ($persenPembimbing >= 100) ? 'bg-success' : 'bg-warning' ?> progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $persenPembimbing ?>%"></div>
                </div>

                <div class="row text-center pt-2 border-top">
                    <div class="col-6 border-right">
                        <div class="text-xs text-muted text-uppercase font-weight-bold">Sudah Berpembimbing</div>
                        <div class="h5 font-weight-bold text-success mb-0"><?= $sudahPembimbing ?> Siswa</div>
                    </div>
                    <div class="col-6">
                        <div class="text-xs text-muted text-uppercase font-weight-bold">Belum Berpembimbing</div>
                        <div class="h5 font-weight-bold text-danger mb-0"><?= $belum_pembimbing ?> Siswa</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Live Monitoring Aktivitas PKL Banner / Widget -->
<div class="card border-0 shadow-sm rounded-xl mb-4 overflow-hidden" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff;">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-3 mb-lg-0">
                <span class="badge badge-light text-primary px-3 py-1 font-weight-bold mb-2">
                    <i class="fas fa-satellite-dish mr-1 text-success"></i> LIVE MONITORING PKL
                </span>
                <h4 class="font-weight-800 text-white mb-1">Aktivitas Siswa PKL Lapangan</h4>
                <p class="text-white-50 text-sm mb-0">Pantau kehadiran harian selfie GPS dan verifikasi jurnal harian seluruh siswa tingkat XII secara real-time.</p>
            </div>
            <div class="col-lg-6">
                <div class="row g-2">
                    <div class="col-sm-6 mb-2">
                        <div class="p-3 rounded-lg" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.12);">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="text-xs text-white-50 text-uppercase font-weight-bold">Presensi Hari Ini</span>
                                <i class="fas fa-camera text-success"></i>
                            </div>
                            <div class="h4 font-weight-800 text-white mb-2"><?= number_format($total_absen_hari_ini ?? 0) ?> Siswa Hadir</div>
                            <a href="<?= site_url('monitoring/absensi') ?>" class="btn btn-sm btn-success w-100 font-weight-bold" style="font-size: 0.75rem;">
                                Pantau Presensi &rarr;
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <div class="p-3 rounded-lg" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.12);">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="text-xs text-white-50 text-uppercase font-weight-bold">Jurnal Menunggu</span>
                                <i class="fas fa-book-reader text-warning"></i>
                            </div>
                            <div class="h4 font-weight-800 text-warning mb-2"><?= number_format($jurnal_menunggu ?? 0) ?> Laporan</div>
                            <a href="<?= site_url('monitoring/jurnal') ?>" class="btn btn-sm btn-warning text-dark w-100 font-weight-bold" style="font-size: 0.75rem;">
                                Audit Jurnal &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monitoring Per Rombel PKL Table -->
<div class="card shadow-sm mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <div>
            <h6 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-tasks mr-2 text-primary"></i> Monitoring Kesiapan Rombel PKL
            </h6>
            <small class="text-muted">Status kelengkapan pembagian jam 46 JP, penempatan DUDI, dan pembimbing per kelas</small>
        </div>
        <a href="<?= site_url('pembagianjam') ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-clock mr-1"></i> Atur Pembagian Jam
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Kelas / Rombel</th>
                        <th>Tingkat</th>
                        <th>Total Siswa</th>
                        <th>Belum DUDI</th>
                        <th>Belum Pembimbing</th>
                        <th>Total Jam Terbagi</th>
                        <th>Status Kelayakan</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($monitoring_rombel)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            Belum ada rombel yang diset sebagai kelas PKL (Status PKL: Ya).
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php $no = 1; foreach ($monitoring_rombel as $r): ?>
                    <tr>
                        <td class="font-weight-bold text-muted"><?= $no++ ?></td>
                        <td>
                            <span class="font-weight-bold text-dark"><?= esc($r->nama_kelas) ?></span>
                        </td>
                        <td><span class="badge badge-light border"><?= esc($r->tingkat) ?></span></td>
                        <td>
                            <span class="font-weight-bold text-primary"><?= $r->total_siswa ?></span> Siswa
                        </td>
                        <td>
                            <?php if ($r->belum_dudi > 0): ?>
                            <span class="badge badge-soft-danger font-weight-bold"><?= $r->belum_dudi ?> Belum</span>
                            <?php else: ?>
                            <span class="badge badge-soft-success font-weight-bold"><i class="fas fa-check mr-1"></i>Lengkap</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($r->belum_pembimbing > 0): ?>
                            <span class="badge badge-soft-danger font-weight-bold"><?= $r->belum_pembimbing ?> Belum</span>
                            <?php else: ?>
                            <span class="badge badge-soft-success font-weight-bold"><i class="fas fa-check mr-1"></i>Lengkap</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="font-weight-bold <?= ($r->total_jp === $jp_wajib) ? 'text-success' : 'text-danger' ?>">
                                <?= $r->total_jp ?> / <?= $jp_wajib ?> JP
                            </span>
                        </td>
                        <td>
                            <?php if ($r->total_jp === $jp_wajib): ?>
                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>Siap Generate</span>
                            <?php else: ?>
                            <span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-exclamation-circle mr-1"></i>Wajib <?= $jp_wajib ?> JP</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="<?= site_url('pembagianjam') ?>" class="btn btn-xs btn-outline-secondary py-1 px-2" title="Kelola Jam">
                                <i class="fas fa-clock"></i>
                            </a>
                            <a href="<?= site_url('pembimbing') ?>" class="btn btn-xs btn-outline-primary py-1 px-2" title="Generate Pembimbing">
                                <i class="fas fa-user-plus"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
