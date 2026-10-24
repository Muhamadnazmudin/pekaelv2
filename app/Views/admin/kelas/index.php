<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Data Kelas / Rombongan Belajar</h1>
        <p class="text-muted text-sm mb-0">Kelola rombel dan tentukan status sasaran PKL (hanya kelas berstatus PKL yang dihitung JJM 46 JP-nya).</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= site_url('dapodik/tarik_kelas') ?>" class="btn btn-outline-success shadow-sm mr-2" onclick="return confirm('Tarik pembaruan rombel tingkat XII dari Dapodik?');">
            <i class="fas fa-sync-alt mr-1"></i> Tarik Rombel Dapodik
        </a>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambahKelas">
            <i class="fas fa-plus-circle mr-1"></i> Tambah Kelas
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
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chalkboard mr-2"></i> Daftar Kelas / Rombel</h6>
        <span class="badge badge-light border text-xs"><?= count($kelas) ?> Rombel Terdata</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 60px;" class="text-center">No</th>
                        <th>Nama Kelas</th>
                        <th style="width: 100px;">Tingkat</th>
                        <th>Kompetensi Keahlian</th>
                        <th class="text-center" style="width: 120px;">Siswa</th>
                        <th class="text-center" style="width: 140px;">Status PKL</th>
                        <th class="text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($kelas)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data rombel. Silakan tambahkan atau tarik dari Dapodik.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($kelas as $k): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <span class="font-weight-bold text-gray-900"><?= esc($k->nama_kelas) ?></span>
                                </td>
                                <td>
                                    <span class="badge badge-light border font-weight-bold"><?= esc($k->tingkat) ?></span>
                                </td>
                                <td>
                                    <span class="text-secondary"><?= esc($k->nama_jurusan ?: '-') ?></span>
                                </td>
                                <td class="text-center font-weight-bold text-gray-800">
                                    <?= esc($k->jumlah_siswa) ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($k->status_pkl === 'ya'): ?>
                                        <a href="<?= site_url('kelas/toggle_pkl/' . $k->id) ?>" class="badge badge-success px-3 py-2 text-decoration-none" title="Klik untuk nonaktifkan PKL">
                                            <i class="fas fa-check mr-1"></i> SASARAN PKL
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= site_url('kelas/toggle_pkl/' . $k->id) ?>" class="badge badge-secondary px-3 py-2 text-decoration-none" title="Klik untuk aktifkan PKL">
                                            NON-PKL
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-warning mr-1 shadow-sm" data-toggle="modal" data-target="#modalEditKelas<?= $k->id ?>" title="Edit Kelas">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?= site_url('kelas/hapus/' . $k->id) ?>" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('Hapus kelas <?= esc($k->nama_kelas) ?>?');" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal Edit Kelas -->
                            <div class="modal fade" id="modalEditKelas<?= $k->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-warning text-white">
                                            <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Kelas</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="<?= site_url('kelas/edit/' . $k->id) ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <div class="modal-body p-4 text-left">
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Nama Kelas</label>
                                                    <input type="text" name="nama_kelas" class="form-control" value="<?= esc($k->nama_kelas) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Tingkat</label>
                                                    <select name="tingkat" class="form-control">
                                                        <option value="XII" <?= $k->tingkat === 'XII' ? 'selected' : '' ?>>Tingkat XII</option>
                                                        <option value="XIII" <?= $k->tingkat === 'XIII' ? 'selected' : '' ?>>Tingkat XIII (SMK 4 Tahun)</option>
                                                        <option value="XI" <?= $k->tingkat === 'XI' ? 'selected' : '' ?>>Tingkat XI</option>
                                                        <option value="X" <?= $k->tingkat === 'X' ? 'selected' : '' ?>>Tingkat X</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Jurusan</label>
                                                    <select name="jurusan_id" class="form-control">
                                                        <option value="0">-- Pilih Jurusan --</option>
                                                        <?php foreach ($jurusan as $j): ?>
                                                            <option value="<?= $j->id ?>" <?= $k->jurusan_id == $j->id ? 'selected' : '' ?>>
                                                                <?= esc($j->nama_jurusan) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Jumlah Siswa</label>
                                                    <input type="number" name="jumlah_siswa" class="form-control" value="<?= esc($k->jumlah_siswa) ?>" min="0">
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Status Sasaran PKL</label>
                                                    <select name="status_pkl" class="form-control">
                                                        <option value="ya" <?= $k->status_pkl === 'ya' ? 'selected' : '' ?>>Ya (Dihitung Alokasi JJM PKL)</option>
                                                        <option value="tidak" <?= $k->status_pkl === 'tidak' ? 'selected' : '' ?>>Tidak (Non-PKL)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning font-weight-bold text-white"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="modalTambahKelas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i> Tambah Kelas</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('kelas/tambah') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: XII RPL 1" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Tingkat</label>
                        <select name="tingkat" class="form-control">
                            <option value="XII" selected>Tingkat XII</option>
                            <option value="XIII">Tingkat XIII (SMK 4 Tahun)</option>
                            <option value="XI">Tingkat XI</option>
                            <option value="X">Tingkat X</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Jurusan</label>
                        <select name="jurusan_id" class="form-control">
                            <option value="0">-- Pilih Jurusan --</option>
                            <?php foreach ($jurusan as $j): ?>
                                <option value="<?= $j->id ?>"><?= esc($j->nama_jurusan) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Jumlah Siswa</label>
                        <input type="number" name="jumlah_siswa" class="form-control" value="36" min="0">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Status Sasaran PKL</label>
                        <select name="status_pkl" class="form-control">
                            <option value="ya" selected>Ya (Dihitung Alokasi JJM PKL)</option>
                            <option value="tidak">Tidak (Non-PKL)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>
