<!-- Page Heading & Quick Actions -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold" style="letter-spacing: -0.02em;">
            <i class="fas fa-book-reader text-primary mr-2"></i> Monitoring &amp; Audit Jurnal PKL
        </h1>
        <p class="text-muted text-sm mb-0">Supervisi seluruh jurnal harian siswa se-sekolah, audit kecepatan verifikasi guru pembimbing, dan intervensi buka kunci laporan.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2">
        <a href="<?= site_url('monitoring/absensi') ?>" class="btn btn-outline-primary shadow-sm mr-2">
            <i class="fas fa-camera mr-1"></i> Ke Monitoring Presensi
        </a>
        <a href="<?= site_url('monitoring/cetak_jurnal?jurusan_id=' . esc($selectedJurusan) . '&kelas_id=' . esc($selectedKelas) . '&status=' . esc($selectedStatus) . '&tanggal=' . esc($selectedTanggal)) ?>" target="_blank" class="btn btn-primary shadow-sm">
            <i class="fas fa-print mr-1"></i> Cetak Rekap Jurnal
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
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-lg mb-4" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Statistik Jurnal Global -->
<div class="row mb-4">
    <!-- Total Jurnal -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-xl overflow-hidden h-100" style="border-left: 4px solid #4f46e5 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Total Laporan Jurnal</div>
                        <div class="h4 font-weight-800 text-gray-900 mb-0"><?= number_format($stats->total) ?></div>
                        <div class="text-xs text-muted mt-1">Seluruh arsip masuk</div>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(79, 70, 229, 0.1); width: 48px; height: 48px;">
                        <i class="fas fa-layer-group text-primary fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menunggu Verifikasi (Highlight) -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-xl overflow-hidden h-100" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Verifikasi</div>
                        <div class="h4 font-weight-800 text-warning mb-0"><?= number_format($stats->menunggu) ?></div>
                        <div class="text-xs text-muted mt-1">Antrean pemeriksaan guru</div>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(245, 158, 11, 0.1); width: 48px; height: 48px;">
                        <i class="fas fa-hourglass-half text-warning fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Disetujui & Terkunci -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-xl overflow-hidden h-100" style="border-left: 4px solid #10b981 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Disetujui (Terkunci)</div>
                        <div class="h4 font-weight-800 text-success mb-0"><?= number_format($stats->disetujui) ?></div>
                        <div class="text-xs text-muted mt-1">Sah &amp; dinilai pembimbing</div>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(16, 185, 129, 0.1); width: 48px; height: 48px;">
                        <i class="fas fa-lock text-success fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Perlu Revisi -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-xl overflow-hidden h-100" style="border-left: 4px solid #ef4444 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Perlu Revisi</div>
                        <div class="h4 font-weight-800 text-danger mb-0"><?= number_format($stats->revisi) ?></div>
                        <div class="text-xs text-muted mt-1">Dikembalikan ke siswa</div>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(239, 68, 68, 0.1); width: 48px; height: 48px;">
                        <i class="fas fa-edit text-danger fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Panel Card -->
<div class="card border-0 shadow-sm rounded-xl mb-4">
    <div class="card-body p-3">
        <form action="<?= site_url('monitoring/jurnal') ?>" method="GET" class="row g-2 align-items-end">
            <!-- Tanggal -->
            <div class="col-md-2 col-sm-6 mb-2">
                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Tanggal Jurnal</label>
                <input type="date" name="tanggal" class="form-control form-control-sm" value="<?= esc($selectedTanggal) ?>" onchange="this.form.submit()">
            </div>

            <!-- Jurusan -->
            <div class="col-md-2 col-sm-6 mb-2">
                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Jurusan</label>
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
                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Kelas</label>
                <select name="kelas_id" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    <?php foreach ($kelasList as $kel): ?>
                        <option value="<?= $kel->id ?>" <?= ($selectedKelas == $kel->id) ? 'selected' : '' ?>>
                            <?= esc($kel->nama_kelas) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Guru Pembimbing -->
            <div class="col-md-2 col-sm-6 mb-2">
                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Guru Pembimbing</label>
                <select name="guru_id" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Pembimbing --</option>
                    <?php foreach ($guruList as $g): ?>
                        <option value="<?= $g->id ?>" <?= ($selectedGuru == $g->id) ? 'selected' : '' ?>>
                            <?= esc($g->nama_guru) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status Verifikasi -->
            <div class="col-md-2 col-sm-6 mb-2">
                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Status Verifikasi</label>
                <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu" <?= ($selectedStatus === 'menunggu') ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                    <option value="disetujui" <?= ($selectedStatus === 'disetujui') ? 'selected' : '' ?>>Disetujui (Terkunci)</option>
                    <option value="revisi" <?= ($selectedStatus === 'revisi') ? 'selected' : '' ?>>Perlu Revisi</option>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="col-md-2 col-sm-6 mb-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 font-weight-bold">
                    <i class="fas fa-filter mr-1"></i> Saring
                </button>
                <a href="<?= site_url('monitoring/jurnal') ?>" class="btn btn-sm btn-light border text-muted" title="Reset Filter">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table Jurnal Card -->
<div class="card border-0 shadow-sm rounded-xl overflow-hidden mb-5">
    <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <h6 class="m-0 font-weight-bold text-gray-900">
                <i class="fas fa-book-open text-primary mr-1"></i> Daftar Laporan Jurnal PKL Siswa
            </h6>
            <span class="text-xs text-muted">Admin dapat melihat isi lengkap, foto kegiatan, catatan evaluasi, membuka kunci, atau melakukan verifikasi.</span>
        </div>
        <span class="badge badge-light border text-xs px-2.5 py-1.5 font-weight-bold">
            <?= count($jurnals) ?> Laporan Masuk
        </span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($jurnals)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-folder-open fa-3x mb-3 text-muted"></i>
                <h6 class="font-weight-bold text-gray-800">Tidak Ada Laporan Jurnal Ditemukan</h6>
                <p class="text-xs mb-0">Belum ada siswa yang mengisi jurnal dengan kriteria filter di atas.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 dataTable" style="width: 100%;">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 40px;" class="text-center">No</th>
                            <th style="width: 105px;">Tanggal</th>
                            <th>Nama Siswa &amp; Kelas</th>
                            <th>Tempat PKL &amp; Pembimbing</th>
                            <th>Kegiatan &amp; Hasil</th>
                            <th style="width: 70px;" class="text-center">Foto</th>
                            <th style="width: 125px;" class="text-center">Status</th>
                            <th style="width: 120px;" class="text-center">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($jurnals as $j): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="font-weight-bold text-gray-900 text-sm">
                                        <?= date('d/m/Y', strtotime($j->tanggal)) ?>
                                    </div>
                                    <div class="text-xs text-muted">
                                        <?= date('H:i', strtotime($j->created_at ?? 'now')) ?> WIB
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-gray-900 text-sm"><?= esc($j->nama_siswa) ?></div>
                                    <div class="text-xs text-muted">
                                        <span class="badge badge-light border"><?= esc($j->nama_kelas ?: '-') ?></span>
                                        NISN: <?= esc($j->nisn ?: '-') ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-primary text-sm">
                                        <i class="fas fa-building text-primary mr-1" style="font-size: 0.8rem;"></i>
                                        <?= esc($j->nama_dudi ?: 'Belum Dipetakan') ?>
                                    </div>
                                    <div class="text-xs text-muted mt-0.5">
                                        <i class="fas fa-user-tie text-muted mr-1"></i>
                                        <?= esc($j->nama_pembimbing ?: ($j->nama_verifikator ?: 'Belum Ditentukan')) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-600 text-gray-900 text-sm mb-1" style="max-width: 320px;">
                                        <?= esc(mb_strimwidth($j->kegiatan, 0, 85, '...')) ?>
                                    </div>
                                    <?php if (!empty($j->kompetensi_dasar)): ?>
                                        <div class="text-xs text-muted">
                                            <i class="fas fa-tag mr-1 text-primary"></i> <?= esc(mb_strimwidth($j->kompetensi_dasar, 0, 45, '...')) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($j->foto)): ?>
                                        <a href="#" data-toggle="modal" data-target="#modalDetailJurnal<?= $j->id ?>">
                                            <img src="<?= base_url('uploads/jurnal/' . $j->foto) ?>" class="rounded shadow-sm border" style="width: 44px; height: 44px; object-fit: cover;" title="Klik untuk lihat foto kegiatan">
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted text-xs">&ndash;</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($j->status_verifikasi === 'disetujui'): ?>
                                        <span class="badge badge-success px-2.5 py-1.5 font-weight-bold" style="font-size: 0.75rem;" title="Laporan sudah disetujui & terkunci bagi siswa">
                                            <i class="fas fa-lock mr-1"></i> Disetujui
                                        </span>
                                    <?php elseif ($j->status_verifikasi === 'revisi'): ?>
                                        <span class="badge badge-danger px-2.5 py-1.5 font-weight-bold" style="font-size: 0.75rem;">
                                            <i class="fas fa-exclamation-circle mr-1"></i> Revisi
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-warning px-2.5 py-1.5 font-weight-bold text-dark" style="font-size: 0.75rem;">
                                            <i class="fas fa-hourglass-half mr-1"></i> Menunggu
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($j->catatan_pembimbing)): ?>
                                        <div class="text-xs text-muted mt-1 font-italic" title="<?= esc($j->catatan_pembimbing) ?>">
                                            "<?= esc(mb_strimwidth($j->catatan_pembimbing, 0, 22, '...')) ?>"
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 4px;">
                                        <!-- Tombol Detail / Verifikasi -->
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalDetailJurnal<?= $j->id ?>" title="Detail & Verifikasi Admin">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Tombol Buka Kunci (Jika Disetujui) -->
                                        <?php if ($j->status_verifikasi === 'disetujui'): ?>
                                            <a href="<?= site_url('monitoring/jurnal_unlock/' . $j->id) ?>" class="btn btn-sm btn-outline-warning" title="Buka Kunci Laporan (Beri Akses Siswa untuk Mengubah)" onclick="return confirm('Buka kunci laporan jurnal ini agar siswa dapat mengedit/memperbaikinya kembali?');">
                                                <i class="fas fa-unlock-alt"></i>
                                            </a>
                                        <?php endif; ?>

                                        <!-- Tombol Hapus -->
                                        <a href="<?= site_url('monitoring/jurnal_hapus/' . $j->id) ?>" class="btn btn-sm btn-outline-danger btn-delete" title="Hapus Jurnal">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Detail & Verifikasi Admin -->
                            <div class="modal fade" id="modalDetailJurnal<?= $j->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow rounded-xl">
                                        <div class="modal-header border-bottom py-3">
                                            <div>
                                                <h6 class="modal-title font-weight-bold text-gray-900">
                                                    <i class="fas fa-book-reader text-primary mr-1"></i> Detail Laporan Jurnal PKL
                                                </h6>
                                                <span class="text-xs text-muted">Tanggal: <?= date('d F Y', strtotime($j->tanggal)) ?> &bull; Siswa: <?= esc($j->nama_siswa) ?></span>
                                            </div>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row">
                                                <!-- Kolom Kiri: Detail Siswa & Pekerjaan -->
                                                <div class="col-md-7">
                                                    <div class="mb-3">
                                                        <span class="text-xs font-weight-bold text-muted text-uppercase d-block mb-1">Identitas Siswa &amp; Tempat PKL</span>
                                                        <div class="p-2.5 rounded bg-light border">
                                                            <div class="font-weight-bold text-gray-900"><?= esc($j->nama_siswa) ?> (NISN: <?= esc($j->nisn ?: '-') ?>)</div>
                                                            <div class="text-xs text-muted">Kelas: <strong><?= esc($j->nama_kelas) ?></strong> &bull; Jurusan: <?= esc($j->nama_jurusan) ?></div>
                                                            <div class="text-xs text-primary font-weight-bold mt-1">
                                                                <i class="fas fa-building mr-1"></i> <?= esc($j->nama_dudi ?: 'Belum Dipetakan') ?>
                                                            </div>
                                                            <div class="text-xs text-muted">
                                                                Pembimbing Lapangan: <strong><?= esc($j->nama_pembimbing ?: 'Belum Ditentukan') ?></strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php if (!empty($j->kompetensi_dasar)): ?>
                                                        <div class="mb-3">
                                                            <span class="text-xs font-weight-bold text-muted text-uppercase d-block mb-1">Elemen / Kompetensi Keahlian</span>
                                                            <div class="p-2 rounded bg-light border text-sm font-weight-500 text-gray-800">
                                                                <?= esc($j->kompetensi_dasar) ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="mb-3">
                                                        <span class="text-xs font-weight-bold text-muted text-uppercase d-block mb-1">Deskripsi Kegiatan yang Dilakukan</span>
                                                        <div class="p-3 rounded bg-light border text-sm text-gray-900 font-weight-500" style="white-space: pre-line; line-height: 1.6;">
                                                            <?= esc($j->kegiatan) ?>
                                                        </div>
                                                    </div>

                                                    <?php if (!empty($j->hasil)): ?>
                                                        <div class="mb-3">
                                                            <span class="text-xs font-weight-bold text-muted text-uppercase d-block mb-1">Hasil Pembelajaran / Temuan di Lapangan</span>
                                                            <div class="p-2.5 rounded bg-light border text-sm text-gray-800" style="white-space: pre-line;">
                                                                <?= esc($j->hasil) ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Kolom Kanan: Foto Dokumentasi & Form Verifikasi Super Admin -->
                                                <div class="col-md-5">
                                                    <span class="text-xs font-weight-bold text-muted text-uppercase d-block mb-1">Foto Dokumentasi</span>
                                                    <div class="text-center p-2 rounded bg-light border mb-3">
                                                        <?php if (!empty($j->foto)): ?>
                                                            <a href="<?= base_url('uploads/jurnal/' . $j->foto) ?>" target="_blank">
                                                                <img src="<?= base_url('uploads/jurnal/' . $j->foto) ?>" class="img-fluid rounded shadow-sm" style="max-height: 220px; object-fit: contain;">
                                                            </a>
                                                            <div class="text-xs text-muted mt-1">Klik gambar untuk membuka ukuran penuh</div>
                                                        <?php else: ?>
                                                            <div class="py-4 text-muted">
                                                                <i class="fas fa-image fa-2x mb-1 text-muted"></i>
                                                                <div class="text-xs">Siswa tidak menyertakan foto</div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Status Verifikasi Terakhir -->
                                                    <div class="p-2.5 rounded border mb-3" style="background: rgba(79, 70, 229, 0.04);">
                                                        <span class="text-xs font-weight-bold text-muted text-uppercase d-block mb-1">Status Saat Ini</span>
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div>
                                                                <?php if ($j->status_verifikasi === 'disetujui'): ?>
                                                                    <span class="badge badge-success font-weight-bold px-2 py-1">
                                                                        <i class="fas fa-lock mr-1"></i> Disetujui (Terkunci)
                                                                    </span>
                                                                <?php elseif ($j->status_verifikasi === 'revisi'): ?>
                                                                    <span class="badge badge-danger font-weight-bold px-2 py-1">
                                                                        <i class="fas fa-exclamation-circle mr-1"></i> Perlu Revisi
                                                                    </span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-warning font-weight-bold px-2 py-1 text-dark">
                                                                        <i class="fas fa-hourglass-half mr-1"></i> Menunggu Verifikasi
                                                                    </span>
                                                                <?php endif; ?>
                                                            </div>
                                                            <?php if (!empty($j->waktu_verifikasi)): ?>
                                                                <span class="text-xs text-muted"><?= date('d/m/Y H:i', strtotime($j->waktu_verifikasi)) ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <?php if (!empty($j->catatan_pembimbing)): ?>
                                                            <div class="text-xs text-gray-800 mt-2 bg-white p-2 rounded border">
                                                                <strong>Catatan:</strong> <?= esc($j->catatan_pembimbing) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Form Intervensi Super Admin -->
                                                    <form action="<?= site_url('monitoring/jurnal_verifikasi/' . $j->id) ?>" method="POST" class="border-top pt-3">
                                                        <?= csrf_field() ?>
                                                        <span class="text-xs font-weight-bold text-primary text-uppercase d-block mb-2">
                                                            <i class="fas fa-shield-alt mr-1"></i> Intervensi Super Admin
                                                        </span>
                                                        <div class="form-group mb-2">
                                                            <label class="text-xs font-weight-bold text-muted mb-1">Pilih Status Baru:</label>
                                                            <select name="status_verifikasi" class="form-control form-control-sm font-weight-bold">
                                                                <option value="disetujui" <?= ($j->status_verifikasi === 'disetujui') ? 'selected' : '' ?>>Disetujui &amp; Kunci Laporan</option>
                                                                <option value="revisi" <?= ($j->status_verifikasi === 'revisi') ? 'selected' : '' ?>>Minta Revisi (Buka Kunci Siswa)</option>
                                                                <option value="menunggu" <?= ($j->status_verifikasi === 'menunggu') ? 'selected' : '' ?>>Kembalikan ke Menunggu (Buka Kunci)</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-2">
                                                            <label class="text-xs font-weight-bold text-muted mb-1">Catatan Admin / Evaluasi (Opsional):</label>
                                                            <textarea name="catatan_pembimbing" class="form-control form-control-sm" rows="2" placeholder="Tuliskan catatan arahan admin..."><?= esc($j->catatan_pembimbing) ?></textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-sm btn-primary btn-block font-weight-bold shadow-sm">
                                                            <i class="fas fa-save mr-1"></i> Simpan Intervensi Status
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
