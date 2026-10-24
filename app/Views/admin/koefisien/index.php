<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Penghitungan Koefisien Pembimbing PKL</h1>
        <p class="text-muted text-sm mb-0">Rumus resmi perhitungan angka koefisien PKL: <code>Koefisien = (Jumlah Rombel &times; JP Wajib) / Jumlah Siswa</code>.</p>
    </div>
    <div class="mt-3 mt-md-0">
        <span class="badge badge-light border text-primary p-2 font-weight-bold">
            <i class="fas fa-calendar-check mr-1"></i> Tahun Aktif: <?= esc($tahun_aktif->tahun ?? '-') ?>
        </span>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Panel Hitung Koefisien -->
    <div class="col-lg-5 mb-4">
        <div class="card shadow border-0">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-calculator mr-2"></i> Hitung Koefisien Tahun Aktif</h6>
            </div>
            <div class="card-body p-4">
                <form action="<?= site_url('koefisien/hitung') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Beban Jam Pelajaran (JP) Wajib per Rombel</label>
                        <div class="input-group">
                            <input type="number" name="jp" class="form-control" value="<?= esc($jp_wajib) ?>" min="1" max="100" required>
                            <div class="input-group-append">
                                <span class="input-group-text font-weight-bold">JP / Minggu</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">Standar kurikulum SMK untuk alokasi JJM PKL Dapodik adalah <strong>46 JP</strong>.</small>
                    </div>

                    <?php if ($preview): ?>
                        <div class="p-3 bg-light rounded border mb-3">
                            <div class="text-xs text-muted font-weight-bold text-uppercase mb-2">Parameter Database Saat Ini:</div>
                            <div class="d-flex justify-content-between mb-1 text-sm">
                                <span>Jumlah Rombel Sasaran PKL:</span>
                                <strong><?= $preview['jumlah_rombel'] ?> Rombel</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1 text-sm">
                                <span>Total JP (Rombel &times; <?= esc($preview['jp_wajib'] ?? $preview['jp'] ?? $jp_wajib ?? 46) ?> JP):</span>
                                <strong><?= esc($preview['total_jam'] ?? ($preview['jumlah_rombel'] * ($preview['jp'] ?? 46))) ?> Jam</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-sm">
                                <span>Total Siswa Sasaran PKL:</span>
                                <strong><?= esc($preview['jumlah_siswa'] ?? 0) ?> Siswa</strong>
                            </div>
                            <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold text-primary">Hasil Koefisien:</span>
                                <span class="h5 mb-0 font-weight-bold text-success"><?= number_format($preview['nilai_koefisien'] ?? $preview['koefisien'] ?? 0, 6) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary btn-block shadow-sm font-weight-bold py-2">
                        <i class="fas fa-sync mr-1"></i> Hitung & Simpan Koefisien
                    </button>
                </form>
            </div>
        </div>

        <!-- Formula Card -->
        <div class="card shadow-sm border-left-info mt-4">
            <div class="card-body p-3">
                <h6 class="font-weight-bold text-info mb-1"><i class="fas fa-info-circle mr-1"></i> Aturan Rumus JJM PKL Dapodik</h6>
                <p class="text-xs text-muted mb-0">
                    Nilai koefisien ini digunakan untuk mengkonversi beban jam mengajar guru (JP) menjadi kuota siswa yang dibimbing secara adil dan terdistribusi presisi untuk Dapodik.
                </p>
            </div>
        </div>
    </div>

    <!-- Panel Riwayat Koefisien -->
    <div class="col-lg-7 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history mr-2"></i> Riwayat Angka Koefisien</h6>
                <span class="badge badge-light border text-xs"><?= count($koefisien) ?> Catatan</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Tahun Pelajaran</th>
                                <th class="text-center">Rombel</th>
                                <th class="text-center">Siswa</th>
                                <th class="text-center">JP</th>
                                <th class="text-center">Koefisien</th>
                                <th class="text-center" style="width: 70px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($koefisien)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Belum ada angka koefisien yang tersimpan. Silakan klik tombol hitung di samping.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($koefisien as $k): ?>
                                    <tr class="<?= ($k->tahun_id == ($tahun_aktif->id ?? 0)) ? 'table-primary-light' : '' ?>">
                                        <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                        <td>
                                            <span class="font-weight-bold text-gray-900"><?= esc($k->tahun) ?></span>
                                            <?php if ($k->tahun_id == ($tahun_aktif->id ?? 0)): ?>
                                                <span class="badge badge-success ml-1">Aktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?= esc($k->jumlah_rombel) ?></td>
                                        <td class="text-center font-weight-bold"><?= esc($k->jumlah_siswa) ?></td>
                                        <td class="text-center"><?= esc($k->jp ?? $k->jp_wajib ?? 46) ?> JP</td>
                                        <td class="text-center font-weight-bold text-success" style="font-size: 1.05rem;">
                                            <?= number_format($k->koefisien ?? $k->nilai_koefisien ?? 0, 6) ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= site_url('koefisien/hapus/' . $k->id) ?>" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('Hapus riwayat koefisien tahun <?= esc($k->tahun) ?>?');" title="Hapus">
                                                <i class="fas fa-trash"></i>
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
    </div>
</div>
