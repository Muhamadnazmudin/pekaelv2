<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Generate Pembimbing (Metode Proporsional Kelas)</h1>
        <p class="text-muted text-sm mb-0">Hitung kuota siswa bimbingan secara proporsional per rombel: <code>Kuota = (Jumlah Siswa Kelas / 46) &times; Jam Guru</code>.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= site_url('pembimbingkelas/generate_semua') ?>" class="btn btn-primary shadow-sm mr-2" onclick="return confirm('Generate kuota proporsional untuk seluruh kelas sasaran PKL sekaligus?');">
            <i class="fas fa-magic mr-1"></i> Generate Semua Kelas
        </a>
        <a href="<?= site_url('pembimbingkelas/reset_semua') ?>" class="btn btn-outline-danger shadow-sm" onclick="return confirm('Apakah Anda yakin ingin mereset seluruh alokasi kuota proporsional tahun ini?');">
            <i class="fas fa-undo mr-1"></i> Reset Semua
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

<div class="row">
    <!-- Panel Generate Per Kelas -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-bolt mr-2"></i> Generate Kuota per Kelas</h6>
            </div>
            <div class="card-body p-4">
                <form action="<?= site_url('pembimbingkelas/generate') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Pilih Kelas Sasaran PKL</label>
                        <select name="kelas_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelas as $k): ?>
                                <option value="<?= $k->id ?>"><?= esc($k->nama_kelas) ?> (<?= esc($k->jumlah_siswa) ?> Siswa)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block shadow-sm font-weight-bold py-2">
                        <i class="fas fa-calculator mr-1"></i> Hitung Proporsional Kelas
                    </button>
                </form>
            </div>
        </div>

        <!-- Ringkasan Kuota per Guru -->
        <div class="card shadow">
            <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-check mr-2"></i> Total Kuota per Guru</h6>
                <span class="badge badge-light border text-xs"><?= count($summary) ?> Guru</span>
            </div>
            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                <?php if (empty($summary)): ?>
                    <div class="p-4 text-center text-muted text-sm">Belum ada kuota yang digenerate.</div>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($summary as $s): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                <div>
                                    <div class="font-weight-bold text-gray-900 text-sm"><?= esc($s->nama_guru) ?></div>
                                    <div class="text-xs text-muted"><?= esc($s->nip ?: '-') ?> &bull; <?= esc($s->total_jam) ?> JP</div>
                                </div>
                                <span class="badge badge-primary badge-pill px-3 py-2 font-weight-bold" style="font-size: 0.9rem;">
                                    <?= esc($s->total_kuota ?? $s->kuota ?? 0) ?> Siswa
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Pembimbing -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list-alt mr-2"></i> Hasil Kuota Proporsional Kelas</h6>
                <span class="badge badge-light border text-xs"><?= count($pembimbing) ?> Baris Alokasi</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Nama Guru Pembimbing</th>
                                <th>Kelas / Rombel</th>
                                <th class="text-center" style="width: 100px;">Jam (JP)</th>
                                <th class="text-center" style="width: 120px;">Kuota Siswa</th>
                                <th class="text-center" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pembimbing)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada hasil generate kuota proporsional. Silakan klik tombol "Generate Semua Kelas" di atas.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($pembimbing as $p): ?>
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                        <td>
                                            <div class="font-weight-bold text-gray-900"><?= esc($p->nama_guru) ?></div>
                                            <div class="text-xs text-muted">NIP: <?= esc($p->nip ?: '-') ?></div>
                                        </td>
                                        <td>
                                            <span class="badge badge-info px-2 py-1"><?= esc($p->nama_kelas) ?></span>
                                        </td>
                                        <td class="text-center font-weight-bold text-gray-700">
                                            <?= esc($p->total_jam) ?> JP
                                        </td>
                                        <td class="text-center font-weight-bold text-success" style="font-size: 1.1rem;">
                                            <?= esc($p->jumlah_bimbingan) ?> Siswa
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= site_url('pembimbingkelas/reset/' . $p->kelas_id) ?>" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('Reset kuota kelas <?= esc($p->nama_kelas) ?>?');" title="Reset Kelas Ini">
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
