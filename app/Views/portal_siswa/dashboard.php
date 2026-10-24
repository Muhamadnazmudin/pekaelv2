<?= $this->extend('portal_siswa/layout') ?>

<?= $this->section('content') ?>

<!-- Hero Student Card -->
<div class="card border-0 shadow-sm rounded-2xl mb-4 overflow-hidden text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);">
    <div class="card-body p-4 p-md-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge badge-light text-primary font-weight-bold px-3 py-1 mb-3 rounded-pill" style="font-size: 0.75rem;">
                    <i class="fas fa-id-badge mr-1"></i> IDENTITAS RESMI SISWA PKL
                </span>
                <h2 class="font-weight-800 mb-1" style="letter-spacing: -0.02em; font-size: 1.85rem;">
                    <?= esc($siswa->nama) ?>
                </h2>
                <div class="d-flex align-items-center flex-wrap text-white-50 text-sm mt-2" style="gap: 12px;">
                    <div><i class="fas fa-barcode mr-1"></i> NISN: <strong class="text-white"><?= esc($siswa->nisn) ?></strong></div>
                    <div>&bull;</div>
                    <div><i class="fas fa-chalkboard mr-1"></i> Kelas: <strong class="text-white"><?= esc($siswa->nama_kelas ?? '-') ?></strong></div>
                    <?php if (!empty($siswa->nama_jurusan)): ?>
                        <div>&bull;</div>
                        <div><i class="fas fa-layer-group mr-1"></i> <?= esc($siswa->nama_jurusan) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-4 mt-3 mt-lg-0 text-lg-right">
                <div class="d-inline-flex flex-column align-items-lg-end bg-white-10 p-3 rounded-xl border border-white-20" style="background: rgba(255, 255, 255, 0.1);">
                    <span class="text-xs text-white-50 font-weight-bold text-uppercase">Status Penempatan</span>
                    <?php if (!empty($siswa->nama_dudi)): ?>
                        <span class="badge badge-success px-3 py-1.5 mt-1 font-weight-bold" style="font-size: 0.85rem;">
                            <i class="fas fa-check-circle mr-1"></i> Aktif di Mitra DUDI
                        </span>
                    <?php else: ?>
                        <span class="badge badge-warning text-dark px-3 py-1.5 mt-1 font-weight-bold" style="font-size: 0.85rem;">
                            <i class="fas fa-clock mr-1"></i> Menunggu Penempatan
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Two Core Cards: Tempat PKL & Guru Pembimbing PKL -->
<div class="row mb-4">
    
    <!-- Card 1: Tempat Prakerin (Mitra DUDI) -->
    <div class="col-lg-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm rounded-xl h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-gray-900">
                    <i class="fas fa-building text-primary mr-2"></i> Tempat Prakerin (Mitra DUDI)
                </h6>
                <?php if (!empty($siswa->dudi_lat) && !empty($siswa->dudi_long)): ?>
                    <span class="badge badge-soft-success px-2.5 py-1 text-xs">
                        <i class="fas fa-map-marker-alt mr-1"></i> GPS Ready (<?= $siswa->radius_meter ?? 100 ?>m)
                    </span>
                <?php else: ?>
                    <span class="badge badge-light border text-muted text-xs">
                        <i class="fas fa-map-marker-alt mr-1"></i> Lokasi GPS Belum Diset
                    </span>
                <?php endif; ?>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($siswa->nama_dudi)): ?>
                    <h4 class="font-weight-bold text-primary mb-2">
                        <?= esc($siswa->nama_dudi) ?>
                    </h4>
                    <p class="text-muted text-sm mb-3">
                        <i class="fas fa-map-marked-alt text-danger mr-1"></i> <?= esc($siswa->alamat_dudi ?: 'Alamat belum dilengkapi.') ?>
                    </p>

                    <div class="p-3 bg-light rounded-lg border text-xs text-secondary mb-3">
                        <div class="row">
                            <div class="col-sm-6 mb-2 mb-sm-0">
                                <span class="text-muted d-block">Pembimbing Industri (PIC):</span>
                                <strong class="text-gray-900"><?= esc($siswa->nama_pic ?: '-') ?></strong>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted d-block">Kontak / No. WhatsApp:</span>
                                <strong class="text-gray-900"><?= esc($siswa->no_hp_pic ?: '-') ?></strong>
                            </div>
                        </div>
                    </div>

                    <a href="<?= site_url('siswa/absensi') ?>" class="btn btn-primary btn-sm font-weight-bold px-3 py-2 rounded-lg shadow-sm">
                        <i class="fas fa-camera mr-1"></i> Buka Presensi GPS Sekarang
                    </a>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-hourglass-half fa-3x text-warning mb-3"></i>
                        <h6 class="font-weight-bold text-gray-800">Sedang Dalam Penempatan</h6>
                        <p class="text-muted text-xs mb-0">Tim Pokja PKL sekolah sedang memetakan mitra industri terbaik untuk kompetensi keahlian Anda.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Card 2: Pembimbing Prakerin (Guru Pembimbing Sekolah) -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-xl h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-gray-900">
                    <i class="fas fa-chalkboard-teacher text-success mr-2"></i> Pembimbing Prakerin (Sekolah)
                </h6>
                <span class="badge badge-light border text-xs">Guru Pembimbing Lapangan</span>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($siswa->nama_pembimbing)): ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center shadow-sm mr-3 font-weight-bold" style="width: 52px; height: 52px; font-size: 1.3rem;">
                            <?= strtoupper(substr($siswa->nama_pembimbing, 0, 1)) ?>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-gray-900 mb-0">
                                <?= esc($siswa->nama_pembimbing) ?>
                            </h5>
                            <span class="text-xs text-muted">NIP: <strong><?= esc($siswa->nip_pembimbing ?: '-') ?></strong></span>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-lg border text-xs text-secondary mb-3">
                        <i class="fas fa-info-circle text-primary mr-1"></i> Guru pembimbing bertugas membimbing, memonitor absensi harian, serta memverifikasi laporan jurnal kegiatan PKL Anda.
                    </div>

                    <a href="<?= site_url('siswa/jurnal') ?>" class="btn btn-outline-success btn-sm font-weight-bold px-3 py-2 rounded-lg">
                        <i class="fas fa-edit mr-1"></i> Tulis Jurnal untuk Pembimbing
                    </a>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-user-clock fa-3x text-secondary mb-3"></i>
                        <h6 class="font-weight-bold text-gray-800">Menunggu Alokasi Pembimbing</h6>
                        <p class="text-muted text-xs mb-0">Guru pembimbing lapangan akan segera ditetapkan oleh Pokja PKL sekolah.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- Status Presensi Hari Ini & Quick Stats -->
<div class="row mb-4">
    
    <!-- Presensi Status Banner -->
    <div class="col-lg-8 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm rounded-xl p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="font-weight-bold text-xs text-uppercase text-muted" style="letter-spacing: 0.05em;">
                    <i class="fas fa-calendar-day mr-1"></i> Status Presensi Hari Ini (<?= date('d M Y') ?>)
                </span>
                <span class="badge badge-pill badge-light border text-xs font-weight-bold px-2.5 py-1">
                    WIB
                </span>
            </div>

            <div class="row align-items-center">
                <div class="col-md-7 mb-3 mb-md-0">
                    <?php if (empty($absenHariIni)): ?>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-danger-light text-danger d-flex align-items-center justify-content-center mr-3" style="width: 44px; height: 44px; font-size: 1.2rem;">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-gray-900 mb-0">Belum Melakukan Absen Masuk</h6>
                                <span class="text-xs text-muted">Segera lakukan absen masuk selfie di lokasi PKL Anda.</span>
                            </div>
                        </div>
                    <?php elseif (empty($absenHariIni->jam_pulang)): ?>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-warning-light text-warning d-flex align-items-center justify-content-center mr-3" style="width: 44px; height: 44px; font-size: 1.2rem;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-gray-900 mb-0">Masuk: <?= esc($absenHariIni->jam_masuk) ?> WIB</h6>
                                <span class="text-xs text-muted">Belum absen pulang. Jangan lupa absen pulang saat jam kerja berakhir.</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success-light text-success d-flex align-items-center justify-content-center mr-3" style="width: 44px; height: 44px; font-size: 1.2rem;">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-success mb-0">Presensi Hari Ini Lengkap</h6>
                                <span class="text-xs text-muted">Masuk: <?= esc($absenHariIni->jam_masuk) ?> WIB &bull; Pulang: <?= esc($absenHariIni->jam_pulang) ?> WIB</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-5 text-md-right">
                    <a href="<?= site_url('siswa/absensi') ?>" class="btn btn-primary btn-block btn-sm font-weight-bold py-2 rounded-lg shadow-sm">
                        <i class="fas fa-map-marker-alt mr-1"></i> Buka Menu Absensi GPS
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stat Numbers -->
    <div class="col-lg-4">
        <div class="row">
            <div class="col-6">
                <div class="card border-0 shadow-sm rounded-xl p-3 text-center mb-3">
                    <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Hadir Bulan Ini</div>
                    <h2 class="font-weight-800 text-primary mb-0"><?= $totalHadirBulanIni ?></h2>
                    <span class="text-xs text-muted">Hari Kerja</span>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-sm rounded-xl p-3 text-center mb-3">
                    <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Jurnal Terisi</div>
                    <h2 class="font-weight-800 text-success mb-0"><?= $totalJurnal ?></h2>
                    <span class="text-xs text-muted">Laporan Kegiatan</span>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Recent Journal Submissions -->
<div class="card border-0 shadow-sm rounded-xl">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-gray-900">
            <i class="fas fa-history text-primary mr-2"></i> Laporan Jurnal Harian Terbaru
        </h6>
        <a href="<?= site_url('siswa/jurnal') ?>" class="text-xs font-weight-bold text-primary">Lihat Semua &rarr;</a>
    </div>
    <div class="card-body p-0">
        <?php if (empty($jurnalTerbaru)): ?>
            <div class="text-center py-4 text-muted text-sm">
                <i class="fas fa-book-open fa-2x mb-2 text-muted"></i>
                <p class="mb-0">Belum ada jurnal yang ditulis. Mulai catat kegiatan PKL harian Anda.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 130px;">Tanggal</th>
                            <th>Uraian Kegiatan PKL</th>
                            <th style="width: 140px;" class="text-center">Status Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jurnalTerbaru as $j): ?>
                            <tr>
                                <td class="font-weight-bold text-gray-800 text-sm">
                                    <i class="fas fa-calendar-alt text-muted mr-1"></i>
                                    <?= date('d/m/Y', strtotime($j->tanggal)) ?>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900 font-weight-600"><?= esc(mb_strimwidth($j->kegiatan, 0, 90, '...')) ?></div>
                                    <?php if (!empty($j->hasil)): ?>
                                        <div class="text-xs text-muted mt-0.5">Hasil: <?= esc(mb_strimwidth($j->hasil, 0, 70, '...')) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($j->status_verifikasi === 'disetujui'): ?>
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i>Disetujui</span>
                                    <?php elseif ($j->status_verifikasi === 'revisi'): ?>
                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation mr-1"></i>Revisi</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i>Menunggu</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
