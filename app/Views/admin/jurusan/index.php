<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Kompetensi Keahlian / Jurusan</h1>
        <p class="text-muted text-sm mb-0">Kelola daftar program keahlian SMK, pemetaan rombel, serta penugasan Kepala Program Keahlian (Kaprog).</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= site_url('dapodik/tarik_jurusan') ?>" class="btn btn-outline-success shadow-sm mr-2" onclick="return confirm('Tarik pembaruan data jurusan dari Dapodik?');">
            <i class="fas fa-sync-alt mr-1"></i> Tarik dari Dapodik
        </a>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambahJurusan">
            <i class="fas fa-plus-circle mr-1"></i> Tambah Jurusan
        </button>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-lg" role="alert" style="background-color: #ecfdf5; color: #065f46; border-left: 4px solid #10b981 !important;">
        <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-lg" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
        <i class="fas fa-exclamation-triangle mr-2"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-network-wired mr-2"></i> Daftar Kompetensi Keahlian &amp; Kepala Program</h6>
        <span class="badge badge-light border text-xs"><?= count($jurusan) ?> Jurusan Terdaftar</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th style="width: 120px;">Kode</th>
                        <th>Nama Kompetensi Keahlian</th>
                        <th style="width: 110px;">Singkatan</th>
                        <th>Kepala Program Keahlian (Kaprog)</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jurusan)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data jurusan. Silakan tambahkan atau tarik dari Dapodik.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($jurusan as $j): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <span class="badge badge-light border text-monospace font-weight-bold px-2 py-1"><?= esc($j->kode_jurusan ?: '-') ?></span>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-gray-900"><?= esc($j->nama_jurusan) ?></span>
                                </td>
                                <td>
                                    <span class="badge badge-primary px-2 py-1"><?= esc($j->singkatan ?: '-') ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($j->nama_kaprog)): ?>
                                        <div class="font-weight-bold text-gray-900" style="font-size: 0.9rem;">
                                            <i class="fas fa-user-tie text-primary mr-1"></i> <?= esc($j->nama_kaprog) ?>
                                        </div>
                                        <div class="text-xs text-muted">NIP: <?= esc($j->nip_kaprog ?: '-') ?></div>
                                    <?php else: ?>
                                        <span class="badge badge-soft-warning px-2 py-1">
                                            <i class="fas fa-user-slash mr-1"></i>Belum Ditentukan
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-warning mr-1 shadow-sm" data-toggle="modal" data-target="#modalEditJurusan<?= $j->id ?>" title="Edit Jurusan / Kaprog">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?= site_url('jurusan/hapus/' . $j->id) ?>" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('Hapus jurusan <?= esc($j->nama_jurusan) ?>?');" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal Edit Jurusan -->
                            <div class="modal fade" id="modalEditJurusan<?= $j->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow-lg rounded-xl">
                                        <div class="modal-header bg-warning text-white">
                                            <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Jurusan &amp; Kepala Program</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="<?= site_url('jurusan/edit/' . $j->id) ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <div class="modal-body p-4 text-left">
                                                <div class="form-group mb-3">
                                                    <label class="font-weight-bold text-gray-700">Nama Kompetensi Keahlian</label>
                                                    <input type="text" name="nama_jurusan" class="form-control" value="<?= esc($j->nama_jurusan) ?>" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="font-weight-bold text-gray-700">Kode Jurusan</label>
                                                    <input type="text" name="kode_jurusan" class="form-control" value="<?= esc($j->kode_jurusan) ?>" placeholder="Contoh: BD, RPL, TKJ">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="font-weight-bold text-gray-700">Singkatan</label>
                                                    <input type="text" name="singkatan" class="form-control" value="<?= esc($j->singkatan) ?>" placeholder="Contoh: BD">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="font-weight-bold text-gray-700">
                                                        <i class="fas fa-user-tie text-primary mr-1"></i> Kepala Program Keahlian (Kaprog)
                                                    </label>
                                                    <select name="kepala_program_id" class="form-control">
                                                        <option value="">-- Belum Ditentukan (Pilih Guru) --</option>
                                                        <?php foreach ($guruList as $g): ?>
                                                            <option value="<?= $g->id ?>" <?= (!empty($j->kepala_program_id) && (int)$j->kepala_program_id === (int)$g->id) ? 'selected' : '' ?>>
                                                                <?= esc($g->nama_guru) ?> (NIP: <?= esc($g->nip ?: '-') ?>)
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <small class="form-text text-muted">Guru yang dipilih otomatis mendapatkan hak akses login monitoring seluruh siswa pada jurusan ini.</small>
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

<!-- Modal Tambah Jurusan -->
<div class="modal fade" id="modalTambahJurusan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-xl">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i> Tambah Jurusan Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('jurusan/tambah') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-gray-700">Nama Kompetensi Keahlian</label>
                        <input type="text" name="nama_jurusan" class="form-control" placeholder="Contoh: Bisnis Digital" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-gray-700">Kode Jurusan</label>
                        <input type="text" name="kode_jurusan" class="form-control" placeholder="Contoh: BD">
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-gray-700">Singkatan</label>
                        <input type="text" name="singkatan" class="form-control" placeholder="Contoh: BD">
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-gray-700">
                            <i class="fas fa-user-tie text-primary mr-1"></i> Kepala Program Keahlian (Kaprog)
                        </label>
                        <select name="kepala_program_id" class="form-control">
                            <option value="">-- Belum Ditentukan (Pilih Guru) --</option>
                            <?php foreach ($guruList as $g): ?>
                                <option value="<?= $g->id ?>">
                                    <?= esc($g->nama_guru) ?> (NIP: <?= esc($g->nip ?: '-') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Guru yang dipilih otomatis mendapatkan hak akses login monitoring seluruh siswa pada jurusan ini.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan Jurusan</button>
                </div>
            </form>
        </div>
    </div>
</div>
