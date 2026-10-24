<!-- Page Heading & Quick Actions -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold" style="letter-spacing: -0.02em;">
            <i class="fas fa-camera text-primary mr-2"></i> Monitoring Presensi Siswa PKL
        </h1>
        <p class="text-muted text-sm mb-0">Pemantauan kehadiran real-time seluruh siswa PKL se-sekolah, foto selfie kedatangan/kepulangan, dan verifikasi radius GPS.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2">
        <a href="<?= site_url('monitoring/jurnal') ?>" class="btn btn-outline-primary shadow-sm mr-2">
            <i class="fas fa-book-reader mr-1"></i> Ke Monitoring Jurnal
        </a>
        <a href="<?= site_url('monitoring/cetak_absensi?tanggal=' . esc($tanggal) . '&jurusan_id=' . esc($selectedJurusan) . '&kelas_id=' . esc($selectedKelas) . '&dudi_id=' . esc($selectedDudi)) ?>" target="_blank" class="btn btn-primary shadow-sm">
            <i class="fas fa-print mr-1"></i> Cetak Lembar Presensi
        </a>
    </div>
</div>

<!-- Alert Notifikasi -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-lg mb-4" role="alert">
        <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Statistik Kehadiran Harian -->
<div class="row mb-4">
    <!-- Total Siswa -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-xl overflow-hidden h-100" style="border-left: 4px solid #4f46e5 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Total Siswa Terfilter</div>
                        <div class="h4 font-weight-800 text-gray-900 mb-0"><?= number_format($stats->totalSiswa) ?></div>
                        <div class="text-xs text-muted mt-1">Siswa tingkat XII</div>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(79, 70, 229, 0.1); width: 48px; height: 48px;">
                        <i class="fas fa-users text-primary fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hadir Hari Ini -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-xl overflow-hidden h-100" style="border-left: 4px solid #10b981 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hadir (<?= esc($stats->persenHadir) ?>%)</div>
                        <div class="h4 font-weight-800 text-success mb-0"><?= number_format($stats->totalHadir) ?></div>
                        <div class="text-xs text-muted mt-1">Selfie GPS terverifikasi</div>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(16, 185, 129, 0.1); width: 48px; height: 48px;">
                        <i class="fas fa-check-circle text-success fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Izin & Sakit -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-xl overflow-hidden h-100" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Izin & Sakit</div>
                        <div class="h4 font-weight-800 text-warning mb-0"><?= number_format($stats->totalIzin + $stats->totalSakit) ?></div>
                        <div class="text-xs text-muted mt-1"><?= $stats->totalIzin ?> Izin &bull; <?= $stats->totalSakit ?> Sakit</div>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(245, 158, 11, 0.1); width: 48px; height: 48px;">
                        <i class="fas fa-file-medical text-warning fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Belum Hadir / Alfa -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-xl overflow-hidden h-100" style="border-left: 4px solid #ef4444 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Belum Hadir / Alfa</div>
                        <div class="h4 font-weight-800 text-danger mb-0"><?= number_format($stats->totalBelum) ?></div>
                        <div class="text-xs text-muted mt-1">Perlu pemantauan guru</div>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(239, 68, 68, 0.1); width: 48px; height: 48px;">
                        <i class="fas fa-user-clock text-danger fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Panel Card -->
<div class="card border-0 shadow-sm rounded-xl mb-4">
    <div class="card-body p-3">
        <form action="<?= site_url('monitoring/absensi') ?>" method="GET" class="row g-2 align-items-end">
            <!-- Tanggal -->
            <div class="col-md-2 col-sm-6 mb-2">
                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Tanggal Presensi</label>
                <input type="date" name="tanggal" class="form-control form-control-sm font-weight-bold" value="<?= esc($tanggal) ?>" onchange="this.form.submit()">
            </div>

            <!-- Jurusan -->
            <div class="col-md-2 col-sm-6 mb-2">
                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Kompetensi Keahlian</label>
                <select name="jurusan_id" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Jurusan --</option>
                    <?php foreach ($jurusanList as $jur): ?>
                        <option value="<?= $jur->id ?>" <?= ($selectedJurusan == $jur->id) ? 'selected' : '' ?>>
                            <?= esc($jur->nama_jurusan) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Kelas -->
            <div class="col-md-2 col-sm-6 mb-2">
                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Rombel / Kelas</label>
                <select name="kelas_id" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    <?php foreach ($kelasList as $kel): ?>
                        <option value="<?= $kel->id ?>" <?= ($selectedKelas == $kel->id) ? 'selected' : '' ?>>
                            <?= esc($kel->nama_kelas) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- DUDI -->
            <div class="col-md-2 col-sm-6 mb-2">
                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Mitra DUDI</label>
                <select name="dudi_id" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Semua DUDI --</option>
                    <?php foreach ($dudiList as $d): ?>
                        <option value="<?= $d->id ?>" <?= ($selectedDudi == $d->id) ? 'selected' : '' ?>>
                            <?= esc($d->nama_dudi) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status Kehadiran -->
            <div class="col-md-2 col-sm-6 mb-2">
                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Status Kehadiran</label>
                <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="hadir" <?= ($selectedStatus === 'hadir') ? 'selected' : '' ?>>Hadir (Selfie)</option>
                    <option value="izin" <?= ($selectedStatus === 'izin') ? 'selected' : '' ?>>Izin</option>
                    <option value="sakit" <?= ($selectedStatus === 'sakit') ? 'selected' : '' ?>>Sakit</option>
                    <option value="belum" <?= ($selectedStatus === 'belum') ? 'selected' : '' ?>>Belum Hadir / Alfa</option>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="col-md-2 col-sm-6 mb-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 font-weight-bold">
                    <i class="fas fa-filter mr-1"></i> Saring
                </button>
                <a href="<?= site_url('monitoring/absensi') ?>" class="btn btn-sm btn-light border text-muted" title="Reset Filter">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Attendance Main Table Card -->
<div class="card border-0 shadow-sm rounded-xl overflow-hidden mb-5">
    <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <h6 class="m-0 font-weight-bold text-gray-900">
                <i class="fas fa-list text-primary mr-1"></i> Data Kehadiran PKL: <?= date('d F Y', strtotime($tanggal)) ?>
            </h6>
            <span class="text-xs text-muted">Menampilkan seluruh siswa tingkat akhir beserta lokasi penempatan & guru pembimbing.</span>
        </div>
        <span class="badge badge-light border text-xs px-2.5 py-1.5 font-weight-bold">
            <?= count($records) ?> Siswa Ditampilkan
        </span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($records)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-user-slash fa-3x mb-3 text-muted"></i>
                <h6 class="font-weight-bold text-gray-800">Tidak Ada Data Siswa Ditemukan</h6>
                <p class="text-xs mb-0">Coba ubah opsi filter pencarian atau tanggal yang dipilih di atas.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 dataTable" style="width: 100%;">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 40px;" class="text-center">No</th>
                            <th>Nama Siswa &amp; NISN</th>
                            <th>Kelas &amp; Jurusan</th>
                            <th>Mitra DUDI &amp; Pembimbing</th>
                            <th style="width: 140px;">Absen Masuk</th>
                            <th style="width: 140px;">Absen Pulang</th>
                            <th style="width: 110px;" class="text-center">Status</th>
                            <th style="width: 90px;" class="text-center">Aksi / GPS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($records as $r): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="font-weight-bold text-gray-900 text-sm"><?= esc($r->nama) ?></div>
                                    <div class="text-xs text-muted font-mono">
                                        NISN: <?= esc($r->nisn ?: '-') ?> &bull; 
                                        <span class="badge badge-light border text-xs"><?= $r->jenis_kelamin === 'P' ? 'Perempuan' : 'Laki-laki' ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 0.72rem;"><?= esc($r->nama_kelas ?: '-') ?></span>
                                    <div class="text-xs text-muted text-truncate mt-1" style="max-width: 180px;" title="<?= esc($r->nama_jurusan) ?>">
                                        <?= esc($r->nama_jurusan ?: '-') ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-gray-900 text-sm">
                                        <i class="fas fa-building text-primary mr-1" style="font-size: 0.8rem;"></i>
                                        <?= esc($r->nama_dudi ?: 'Belum Dipetakan') ?>
                                    </div>
                                    <div class="text-xs text-muted mt-0.5">
                                        <i class="fas fa-chalkboard-teacher text-muted mr-1"></i>
                                        Pembimbing: <strong><?= esc($r->nama_pembimbing ?: 'Belum Ditentukan') ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($r->jam_masuk)): ?>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($r->foto_masuk)): ?>
                                                <a href="#" data-toggle="modal" data-target="#modalFotoMasuk<?= $r->id ?>" class="mr-2">
                                                    <img src="<?= base_url('uploads/absensi/' . $r->foto_masuk) ?>" class="rounded-circle shadow-sm border" style="width: 36px; height: 36px; object-fit: cover;" title="Klik untuk perbesar selfie masuk">
                                                </a>
                                            <?php endif; ?>
                                            <div>
                                                <div class="font-weight-bold text-gray-900 font-mono text-sm">
                                                    <i class="fas fa-sign-in-alt text-success mr-1"></i> <?= esc($r->jam_masuk) ?>
                                                </div>
                                                <?php if (!empty($r->jarak_masuk)): ?>
                                                    <div class="text-xs text-muted">Jarak: <?= round($r->jarak_masuk) ?>m</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-muted">&ndash;</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($r->jam_pulang)): ?>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($r->foto_pulang)): ?>
                                                <a href="#" data-toggle="modal" data-target="#modalFotoPulang<?= $r->id ?>" class="mr-2">
                                                    <img src="<?= base_url('uploads/absensi/' . $r->foto_pulang) ?>" class="rounded-circle shadow-sm border" style="width: 36px; height: 36px; object-fit: cover;" title="Klik untuk perbesar selfie pulang">
                                                </a>
                                            <?php endif; ?>
                                            <div>
                                                <div class="font-weight-bold text-gray-900 font-mono text-sm">
                                                    <i class="fas fa-sign-out-alt text-warning mr-1"></i> <?= esc($r->jam_pulang) ?>
                                                </div>
                                                <?php if (!empty($r->jarak_pulang)): ?>
                                                    <div class="text-xs text-muted">Jarak: <?= round($r->jarak_pulang) ?>m</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-muted">&ndash;</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($r->status_absen === 'hadir'): ?>
                                        <span class="badge badge-success px-2.5 py-1.5 font-weight-bold" style="font-size: 0.75rem;">
                                            <i class="fas fa-check-circle mr-1"></i> Hadir
                                        </span>
                                    <?php elseif ($r->status_absen === 'izin'): ?>
                                        <span class="badge badge-warning px-2.5 py-1.5 font-weight-bold" style="font-size: 0.75rem;">
                                            <i class="fas fa-info-circle mr-1"></i> Izin
                                        </span>
                                    <?php elseif ($r->status_absen === 'sakit'): ?>
                                        <span class="badge badge-info px-2.5 py-1.5 font-weight-bold text-white" style="font-size: 0.75rem;">
                                            <i class="fas fa-notes-medical mr-1"></i> Sakit
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-light border text-danger px-2.5 py-1.5 font-weight-bold" style="font-size: 0.75rem;">
                                            <i class="fas fa-times-circle mr-1"></i> Belum
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($r->keterangan_absen)): ?>
                                        <div class="text-xs text-muted mt-1 font-italic" title="<?= esc($r->keterangan_absen) ?>">
                                            "<?= esc(mb_strimwidth($r->keterangan_absen, 0, 20, '...')) ?>"
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($r->lat_masuk) && !empty($r->long_masuk)): ?>
                                        <a href="https://www.google.com/maps?q=<?= esc($r->lat_masuk) ?>,<?= esc($r->long_masuk) ?>" target="_blank" class="btn btn-sm btn-light border text-primary" title="Buka Koordinat GPS di Google Maps">
                                            <i class="fas fa-map-marker-alt text-danger"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted text-xs">&ndash;</span>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Modal Selfie Masuk -->
                            <?php if (!empty($r->foto_masuk)): ?>
                            <div class="modal fade" id="modalFotoMasuk<?= $r->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow rounded-xl">
                                        <div class="modal-header border-bottom py-2.5">
                                            <h6 class="modal-title font-weight-bold text-gray-900">
                                                <i class="fas fa-camera text-success mr-1"></i> Selfie Masuk: <?= esc($r->nama) ?>
                                            </h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-center p-3">
                                            <img src="<?= base_url('uploads/absensi/' . $r->foto_masuk) ?>" class="img-fluid rounded-lg shadow-sm mb-3" style="max-height: 420px; object-fit: contain;">
                                            <div class="text-xs text-muted">
                                                Waktu Masuk: <strong><?= esc($r->jam_masuk) ?></strong> &bull; Jarak: <strong><?= round($r->jarak_masuk ?? 0) ?> Meter</strong> dari radius DUDI
                                            </div>
                                            <?php if (!empty($r->lat_masuk)): ?>
                                                <div class="mt-2">
                                                    <a href="https://www.google.com/maps?q=<?= esc($r->lat_masuk) ?>,<?= esc($r->long_masuk) ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-map-marked-alt mr-1"></i> Buka Titik Lokasi Masuk di Google Maps
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Modal Selfie Pulang -->
                            <?php if (!empty($r->foto_pulang)): ?>
                            <div class="modal fade" id="modalFotoPulang<?= $r->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow rounded-xl">
                                        <div class="modal-header border-bottom py-2.5">
                                            <h6 class="modal-title font-weight-bold text-gray-900">
                                                <i class="fas fa-camera text-warning mr-1"></i> Selfie Pulang: <?= esc($r->nama) ?>
                                            </h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-center p-3">
                                            <img src="<?= base_url('uploads/absensi/' . $r->foto_pulang) ?>" class="img-fluid rounded-lg shadow-sm mb-3" style="max-height: 420px; object-fit: contain;">
                                            <div class="text-xs text-muted">
                                                Waktu Pulang: <strong><?= esc($r->jam_pulang) ?></strong> &bull; Jarak: <strong><?= round($r->jarak_pulang ?? 0) ?> Meter</strong>
                                            </div>
                                            <?php if (!empty($r->lat_pulang)): ?>
                                                <div class="mt-2">
                                                    <a href="https://www.google.com/maps?q=<?= esc($r->lat_pulang) ?>,<?= esc($r->long_pulang) ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-map-marked-alt mr-1"></i> Buka Titik Lokasi Pulang di Google Maps
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
