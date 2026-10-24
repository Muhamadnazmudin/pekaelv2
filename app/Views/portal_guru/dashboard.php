<?= $this->extend('portal_guru/layout') ?>

<?= $this->section('content') ?>

<!-- Teacher Hero Header -->
<div class="card border-0 shadow-sm rounded-2xl mb-4 text-white overflow-hidden" style="background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%);">
    <div class="card-body p-4 p-md-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge badge-light text-success font-weight-bold px-3 py-1 mb-3 rounded-pill" style="font-size: 0.75rem;">
                    <i class="fas fa-chalkboard-teacher mr-1"></i> AKUN GURU SMK RESMI
                </span>
                <h2 class="font-weight-800 mb-1" style="letter-spacing: -0.02em; font-size: 1.85rem;">
                    <?= esc($guru->nama_guru) ?>
                </h2>
                <div class="d-flex align-items-center flex-wrap text-white-50 text-sm mt-2" style="gap: 12px;">
                    <div><i class="fas fa-id-card mr-1"></i> NIP: <strong class="text-white"><?= esc($guru->nip ?: 'Non-NIP') ?></strong></div>
                    <div>&bull;</div>
                    <div><i class="fas fa-user-tag mr-1"></i> Jenis Guru: <strong class="text-white"><?= ucfirst(esc($guru->jenis_guru ?: 'Produktif')) ?></strong></div>
                </div>
            </div>
            <div class="col-lg-4 mt-3 mt-lg-0 text-lg-right">
                <div class="d-inline-flex flex-column align-items-lg-end" style="gap: 6px;">
                    <?php if ($isPembimbing): ?>
                        <span class="badge badge-success px-3 py-1.5 font-weight-bold shadow-sm" style="font-size: 0.85rem; border: 1px solid rgba(255,255,255,0.4);">
                            <i class="fas fa-check-circle mr-1"></i> Guru Pembimbing PKL
                        </span>
                    <?php endif; ?>
                    <?php if ($isKaprog): ?>
                        <span class="badge badge-warning text-dark px-3 py-1.5 font-weight-bold shadow-sm" style="font-size: 0.85rem;">
                            <i class="fas fa-award mr-1"></i> Kepala Program Keahlian
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Special Kaprog Banner (If Kaprog) -->
<?php if ($isKaprog): ?>
    <div class="card border-0 shadow-sm rounded-xl p-4 mb-4" style="background-color: #f0fdf4; border-left: 5px solid #16a34a !important;">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div class="mb-3 mb-md-0">
                <h5 class="font-weight-bold text-gray-900 mb-1">
                    <i class="fas fa-layer-group text-success mr-2"></i> Akses Khusus Kepala Program Keahlian (Kaprog)
                </h5>
                <p class="text-muted text-xs mb-0">
                    Anda ditugaskan membina program keahlian: 
                    <?php foreach ($kaprogJurusans as $kj): ?>
                        <strong class="text-dark"><?= esc($kj->nama_jurusan) ?> (<?= esc($kj->singkatan) ?>)</strong><?= next($kaprogJurusans) ? ', ' : '' ?>
                    <?php endforeach; ?>.
                    Anda dapat memonitoring seluruh kelas, siswa, penempatan DUDI, dan absensi di jurusan binaan Anda.
                </p>
            </div>
            <div>
                <a href="<?= site_url('guru/kaprog') ?>" class="btn btn-success font-weight-bold px-4 py-2 rounded-xl shadow-sm text-white">
                    <i class="fas fa-chart-line mr-1.5"></i> Buka Monitoring Jurusan &rarr;
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Case 1: Guru BUKAN Pembimbing PKL -->
<?php if (!$isPembimbing): ?>
    <div class="card border-0 shadow-sm rounded-2xl p-5 text-center my-4">
        <div class="rounded-circle bg-light d-inline-flex p-4 text-muted mx-auto mb-3" style="width: 80px; height: 80px; align-items: center; justify-content: center;">
            <i class="fas fa-user-slash fa-2x text-secondary"></i>
        </div>
        <h4 class="font-weight-bold text-gray-800 mb-2">Anda Bukan Pembimbing PKL</h4>
        <p class="text-muted text-sm mx-auto mb-4" style="max-width: 540px;">
            Saat ini Anda tidak memiliki alokasi siswa bimbingan Praktik Kerja Lapangan (PKL) untuk tahun pelajaran aktif. Jika terdapat kekeliruan alokasi jam mengajar mingguan (JJM) PKL, silakan berkoordinasi dengan Tim Pokja PKL sekolah.
        </p>
        <div class="d-inline-flex justify-content-center">
            <a href="<?= site_url('auth/logout') ?>" class="btn btn-outline-secondary btn-sm font-weight-bold px-3 py-2 rounded-lg">
                <i class="fas fa-sign-out-alt mr-1"></i> Keluar dari Portal
            </a>
        </div>
    </div>

<!-- Case 2: Guru ADALAH Pembimbing PKL (Tampilkan Data Siswa & Kegiatan) -->
<?php else: ?>

    <!-- KPI Metric Cards for Pembimbing -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm rounded-xl p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-xs font-weight-bold text-muted text-uppercase">Total Siswa Bimbingan</span>
                    <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <h3 class="font-weight-800 text-gray-900 mb-0"><?= $totalBimbingan ?></h3>
                <span class="text-xs text-muted">Siswa Aktif Prakerin</span>
            </div>
        </div>

        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm rounded-xl p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-xs font-weight-bold text-muted text-uppercase">Hadir Hari Ini (<?= date('d/m') ?>)</span>
                    <div class="rounded-circle bg-success-light text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <h3 class="font-weight-800 text-success mb-0"><?= $hadirHariIni ?> / <?= $totalBimbingan ?></h3>
                <span class="text-xs text-muted"><?= round(($totalBimbingan > 0 ? ($hadirHariIni/$totalBimbingan)*100 : 0)) ?>% Persentase Kehadiran</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-xl p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-xs font-weight-bold text-muted text-uppercase">Jurnal Menunggu Validasi</span>
                    <div class="rounded-circle bg-warning-light text-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fas fa-book-reader"></i>
                    </div>
                </div>
                <h3 class="font-weight-800 text-warning mb-0"><?= $jurnalMenunggu ?></h3>
                <span class="text-xs text-muted">Laporan Perlu Ditinjau</span>
            </div>
        </div>
    </div>

    <!-- Mentored Students List Table -->
    <div class="card border-0 shadow-sm rounded-xl overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-gray-900">
                <i class="fas fa-user-graduate text-primary mr-2"></i> Daftar Siswa yang Anda Bimbing
            </h6>
            <div class="d-flex" style="gap: 8px;">
                <a href="<?= site_url('guru/absensi') ?>" class="btn btn-sm btn-outline-primary font-weight-bold rounded-lg">
                    <i class="fas fa-camera mr-1"></i> Monitoring Absensi
                </a>
                <a href="<?= site_url('guru/jurnal') ?>" class="btn btn-sm btn-primary font-weight-bold rounded-lg shadow-sm">
                    <i class="fas fa-check-double mr-1"></i> Validasi Jurnal (<?= $jurnalMenunggu ?>)
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th>Nama Siswa &amp; NISN</th>
                            <th>Kelas &amp; Jurusan</th>
                            <th>Tempat PKL (Mitra DUDI)</th>
                            <th style="width: 150px;" class="text-center">Presensi Hari Ini</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($siswaBimbingan)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data siswa bimbingan.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($siswaBimbingan as $s): ?>
                                <tr>
                                    <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                    <td>
                                        <div class="font-weight-bold text-gray-900 text-sm"><?= esc($s->nama) ?></div>
                                        <div class="text-xs text-muted">NISN: <?= esc($s->nisn) ?></div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light border text-dark font-weight-bold"><?= esc($s->nama_kelas) ?></span>
                                        <div class="text-xs text-muted mt-0.5"><?= esc($s->nama_jurusan) ?></div>
                                    </td>
                                    <td>
                                        <?php if (!empty($s->nama_dudi)): ?>
                                            <div class="font-weight-bold text-primary text-sm">
                                                <i class="fas fa-building mr-1"></i> <?= esc($s->nama_dudi) ?>
                                            </div>
                                            <div class="text-xs text-muted"><?= esc($s->alamat_dudi ?: '-') ?></div>
                                        <?php else: ?>
                                            <span class="badge badge-warning text-dark px-2 py-0.5 text-xs">Belum Ada DUDI</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($s->status_absen_hari_ini === 'hadir'): ?>
                                            <span class="badge badge-soft-success px-2.5 py-1 text-xs font-weight-bold">
                                                <i class="fas fa-check mr-1"></i> Masuk: <?= esc($s->jam_masuk) ?>
                                            </span>
                                            <?php if (!empty($s->jam_pulang)): ?>
                                                <div class="text-xs text-muted mt-0.5">Pulang: <?= esc($s->jam_pulang) ?></div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge badge-light border text-danger px-2.5 py-1 text-xs font-weight-bold">
                                                <i class="fas fa-times-circle mr-1"></i> Belum Absen
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php endif; ?>

<?= $this->endSection() ?>
