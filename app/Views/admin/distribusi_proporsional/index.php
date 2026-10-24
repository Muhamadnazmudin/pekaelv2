<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Distribusi Siswa (Metode Proporsional Kelas)</h1>
        <p class="text-muted text-sm mb-0">Petakan dan pilih siswa asuh untuk masing-masing guru pembimbing sesuai kuota metode proporsional kelas.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= site_url('pembimbingkelas') ?>" class="btn btn-outline-primary shadow-sm mr-2">
            <i class="fas fa-calculator mr-1"></i> Cek Kuota Proporsional
        </a>
        <a href="<?= site_url('distribusi_proporsional/reset') ?>" class="btn btn-outline-danger shadow-sm" onclick="return confirm('Apakah Anda yakin ingin mereset seluruh distribusi siswa metode proporsional?');">
            <i class="fas fa-undo mr-1"></i> Reset Distribusi
        </a>
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

<!-- Monitoring Progress Kuota per Guru -->
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-tasks mr-2"></i> Status Pengisian Kuota Proporsional per Guru</h6>
        <span class="badge badge-light border text-xs"><?= count($pembimbing) ?> Guru Pembimbing</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Nama Guru Pembimbing</th>
                        <th>NIP</th>
                        <th class="text-center" style="width: 100px;">Jam (JP)</th>
                        <th class="text-center" style="width: 140px;">Kuota Siswa</th>
                        <th class="text-center" style="width: 140px;">Terpilih</th>
                        <th style="width: 180px;">Progress</th>
                        <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pembimbing)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada kuota pembimbing proporsional. Silakan hitung di menu "Generate Pembimbing Kelas" terlebih dahulu.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($pembimbing as $p): ?>
                            <?php 
                                $kuota = (int)$p->kuota;
                                $terbimbing = (int)$p->terbimbing;
                                $persen = $kuota > 0 ? min(100, round(($terbimbing / $kuota) * 100)) : 0;
                                $isLengkap = $kuota > 0 && $terbimbing >= $kuota;
                            ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="font-weight-bold text-gray-900"><?= esc($p->nama_guru) ?></div>
                                </td>
                                <td>
                                    <span class="text-monospace text-secondary"><?= esc($p->nip ?: '-') ?></span>
                                </td>
                                <td class="text-center font-weight-bold text-gray-700">
                                    <?= esc($p->total_jam) ?> JP
                                </td>
                                <td class="text-center font-weight-bold text-primary" style="font-size: 1.05rem;">
                                    <?= $kuota ?> Siswa
                                </td>
                                <td class="text-center font-weight-bold <?= $isLengkap ? 'text-success' : 'text-warning' ?>" style="font-size: 1.05rem;">
                                    <?= $terbimbing ?> Siswa
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="text-xs font-weight-bold mr-2 text-muted"><?= $persen ?>%</span>
                                        <div class="progress flex-grow-1 progress-sm">
                                            <div class="progress-bar <?= $isLengkap ? 'bg-success' : 'bg-warning' ?>" role="progressbar" style="width: <?= $persen ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('distribusi_proporsional/mapping/' . $p->guru_id) ?>" class="btn btn-sm btn-primary shadow-sm font-weight-bold px-3">
                                        <i class="fas fa-user-edit mr-1"></i> Pilih Siswa
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
