<?= $this->extend('portal_guru/layout') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h4 class="font-weight-800 text-gray-900 mb-1" style="letter-spacing: -0.02em;">
            <i class="fas fa-camera text-primary mr-2"></i> Monitoring Presensi Siswa Bimbingan
        </h4>
        <p class="text-muted text-xs mb-0">Pantau kehadiran real-time, foto selfie masuk/pulang, dan jarak GPS siswa bimbingan Anda.</p>
    </div>
    
    <!-- Date Filter Form -->
    <div class="mt-3 mt-md-0">
        <form action="<?= site_url('guru/absensi') ?>" method="GET" class="d-flex align-items-center" style="gap: 8px;">
            <label class="text-xs font-weight-bold text-muted mb-0 mr-1 text-nowrap">PILIH TANGGAL:</label>
            <input type="date" name="tanggal" class="form-control form-control-sm font-weight-bold" value="<?= esc($tanggal) ?>" onchange="this.form.submit()">
            <button type="submit" class="btn btn-sm btn-primary font-weight-bold">
                <i class="fas fa-filter"></i>
            </button>
        </form>
    </div>
</div>

<!-- Attendance Stats Banner -->
<?php 
    $totalHadir = 0;
    foreach ($absensi as $a) {
        if ($a->status === 'hadir') $totalHadir++;
    }
?>
<div class="row mb-4">
    <div class="col-sm-4 mb-2 mb-sm-0">
        <div class="card border-0 shadow-sm rounded-xl p-3 text-center">
            <span class="text-xs font-weight-bold text-muted text-uppercase mb-1">Total Siswa Bimbingan</span>
            <h3 class="font-weight-800 text-gray-900 mb-0"><?= $totalBimbingan ?></h3>
        </div>
    </div>
    <div class="col-sm-4 mb-2 mb-sm-0">
        <div class="card border-0 shadow-sm rounded-xl p-3 text-center">
            <span class="text-xs font-weight-bold text-muted text-uppercase mb-1">Hadir (<?= date('d M Y', strtotime($tanggal)) ?>)</span>
            <h3 class="font-weight-800 text-success mb-0"><?= $totalHadir ?></h3>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm rounded-xl p-3 text-center">
            <span class="text-xs font-weight-bold text-muted text-uppercase mb-1">Belum Hadir / Absen</span>
            <h3 class="font-weight-800 text-danger mb-0"><?= max(0, $totalBimbingan - $totalHadir) ?></h3>
        </div>
    </div>
</div>

<!-- Attendance Table -->
<div class="card border-0 shadow-sm rounded-xl overflow-hidden">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-gray-900">
            <i class="fas fa-list text-primary mr-1"></i> Data Kehadiran Tanggal <?= date('d F Y', strtotime($tanggal)) ?>
        </h6>
        <span class="badge badge-light border text-xs"><?= count($absensi) ?> Record Ditemukan</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($absensi)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-calendar-times fa-3x mb-2 text-muted"></i>
                <h6 class="font-weight-bold text-gray-800">Tidak Ada Presensi Tercatat</h6>
                <p class="text-xs mb-0">Belum ada siswa bimbingan yang melakukan presensi selfie GPS pada tanggal ini.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th>Nama Siswa &amp; Kelas</th>
                            <th>Tempat PKL (DUDI)</th>
                            <th style="width: 140px;">Absen Masuk</th>
                            <th style="width: 140px;">Absen Pulang</th>
                            <th style="width: 120px;" class="text-center">Jarak GPS</th>
                            <th style="width: 100px;" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($absensi as $ab): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="font-weight-bold text-gray-900 text-sm"><?= esc($ab->nama_siswa) ?></div>
                                    <div class="text-xs text-muted">NISN: <?= esc($ab->nisn) ?> &bull; <?= esc($ab->nama_kelas) ?></div>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-primary text-sm"><?= esc($ab->nama_dudi ?: '-') ?></div>
                                </td>
                                <td>
                                    <?php if (!empty($ab->jam_masuk)): ?>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($ab->foto_masuk)): ?>
                                                <a href="<?= base_url('uploads/absensi/' . $ab->foto_masuk) ?>" target="_blank" class="mr-2">
                                                    <img src="<?= base_url('uploads/absensi/' . $ab->foto_masuk) ?>" class="rounded-circle shadow-sm border" style="width: 36px; height: 36px; object-fit: cover;" title="Lihat Selfie Masuk">
                                                </a>
                                            <?php endif; ?>
                                            <div>
                                                <strong class="text-gray-900 text-sm"><?= esc($ab->jam_masuk) ?></strong>
                                                <span class="text-xs text-muted d-block">WIB</span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted text-xs">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($ab->jam_pulang)): ?>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($ab->foto_pulang)): ?>
                                                <a href="<?= base_url('uploads/absensi/' . $ab->foto_pulang) ?>" target="_blank" class="mr-2">
                                                    <img src="<?= base_url('uploads/absensi/' . $ab->foto_pulang) ?>" class="rounded-circle shadow-sm border" style="width: 36px; height: 36px; object-fit: cover;" title="Lihat Selfie Pulang">
                                                </a>
                                            <?php endif; ?>
                                            <div>
                                                <strong class="text-warning text-sm"><?= esc($ab->jam_pulang) ?></strong>
                                                <span class="text-xs text-muted d-block">WIB</span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge badge-light border text-muted text-xs">Belum Pulang</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($ab->jarak_masuk !== null): ?>
                                        <?php $maxR = $ab->radius_meter ?: 100; ?>
                                        <?php if ($ab->jarak_masuk <= ($maxR + 50)): ?>
                                            <span class="badge badge-soft-success px-2 py-1 text-xs" title="Di dalam radius DUDI (Maks: <?= $maxR ?>m)">
                                                <i class="fas fa-check-circle mr-1"></i><?= $ab->jarak_masuk ?> meter
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-soft-danger px-2 py-1 text-xs" title="Di luar radius DUDI">
                                                <i class="fas fa-exclamation-triangle mr-1"></i><?= $ab->jarak_masuk ?> meter
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($ab->lat_masuk) && !empty($ab->long_masuk)): ?>
                                            <div class="mt-1">
                                                <a href="https://maps.google.com/?q=<?= $ab->lat_masuk ?>,<?= $ab->long_masuk ?>" target="_blank" class="text-xs text-primary font-weight-bold">
                                                    <i class="fas fa-map-marked-alt mr-1"></i>Peta
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge badge-light border text-muted text-xs">Tanpa Radius</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($ab->status === 'hadir'): ?>
                                        <span class="badge badge-success px-2.5 py-1">Hadir</span>
                                    <?php elseif ($ab->status === 'izin'): ?>
                                        <span class="badge badge-info px-2.5 py-1">Izin</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger px-2.5 py-1">Alpha</span>
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
