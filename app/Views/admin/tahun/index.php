<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Tahun Pelajaran</h1>
        <p class="text-muted text-sm mb-0">Kelola periode tahun pelajaran aktif yang menjadi acuan perhitungan JJM PKL dan penarikan Dapodik.</p>
    </div>
    <div class="mt-3 mt-md-0">
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambahTahun">
            <i class="fas fa-plus-circle mr-1"></i> Tambah Tahun Pelajaran
        </button>
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

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-calendar-alt mr-2"></i> Daftar Tahun Pelajaran</h6>
        <span class="badge badge-light border text-xs"><?= count($tahun) ?> Periode Terdaftar</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 60px;" class="text-center">No</th>
                        <th>Tahun Pelajaran</th>
                        <th class="text-center" style="width: 150px;">Status</th>
                        <th class="text-center" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tahun)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada data tahun pelajaran.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($tahun as $t): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <span class="font-weight-bold text-gray-900" style="font-size: 1.05rem;"><?= esc($t->tahun) ?></span>
                                    <?php if ($t->status === 'aktif'): ?>
                                        <span class="badge badge-success ml-2 px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Sedang Digunakan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($t->status === 'aktif'): ?>
                                        <span class="badge badge-pill badge-success px-3 py-2">AKTIF</span>
                                    <?php else: ?>
                                        <span class="badge badge-pill badge-secondary px-3 py-2">NONAKTIF</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($t->status !== 'aktif'): ?>
                                        <a href="<?= site_url('tahun/set_aktif/' . $t->id) ?>" class="btn btn-sm btn-outline-success mr-1 shadow-sm" onclick="return confirm('Aktifkan tahun pelajaran <?= esc($t->tahun) ?>?');" title="Jadikan Tahun Aktif">
                                            <i class="fas fa-power-off mr-1"></i> Aktifkan
                                        </a>
                                        <a href="<?= site_url('tahun/hapus/' . $t->id) ?>" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('Hapus tahun pelajaran <?= esc($t->tahun) ?>?');" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="badge badge-light border text-muted px-2 py-1"><i class="fas fa-lock mr-1"></i> Terkunci (Aktif)</span>
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

<!-- Modal Tambah Tahun -->
<div class="modal fade" id="modalTambahTahun" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-calendar-plus mr-2"></i> Tambah Tahun Pelajaran</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('tahun/tambah') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Tahun Pelajaran</label>
                        <input type="text" name="tahun" class="form-control" placeholder="Contoh: 2024/2025" required>
                        <small class="form-text text-muted">Gunakan format tahun seperti 2024/2025 atau 2025/2026.</small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Status</label>
                        <select name="status" class="form-control">
                            <option value="nonaktif">Nonaktif</option>
                            <option value="aktif">Langsung Jadikan Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan Tahun</button>
                </div>
            </form>
        </div>
    </div>
</div>
