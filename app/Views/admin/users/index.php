<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Manajemen Pengguna / Operator</h1>
        <p class="text-muted text-sm mb-0">Kelola akun operator sekolah yang memiliki hak akses pengelolaan data PKL dan integrasi Dapodik.</p>
    </div>
    <div class="mt-3 mt-md-0">
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambahUser">
            <i class="fas fa-user-plus mr-1"></i> Tambah Pengguna Baru
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
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users-cog mr-2"></i> Daftar Pengguna Sistem</h6>
        <span class="badge badge-light border text-xs"><?= count($users) ?> Akun Terdaftar</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th class="text-center" style="width: 140px;">Role</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada akun pengguna.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($users as $u): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <span class="font-weight-bold text-gray-900"><?= esc($u->username) ?></span>
                                    <?php if ($u->id == session()->get('user_id')): ?>
                                        <span class="badge badge-success ml-2 px-2 py-1"><i class="fas fa-user-check mr-1"></i> Akun Anda</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($u->nama_lengkap ?: $u->username) ?></td>
                                <td class="text-center">
                                    <span class="badge badge-primary px-2 py-1 text-uppercase"><?= esc($u->role ?: 'admin') ?></span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-warning mr-1 shadow-sm" data-toggle="modal" data-target="#modalEditUser<?= $u->id ?>" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php if ($u->id != session()->get('user_id')): ?>
                                        <a href="<?= site_url('users/hapus/' . $u->id) ?>" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('Hapus akun <?= esc($u->username) ?>?');" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Modal Edit User -->
                            <div class="modal fade" id="modalEditUser<?= $u->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-warning text-white">
                                            <h5 class="modal-title font-weight-bold"><i class="fas fa-user-edit mr-2"></i> Edit Akun Pengguna</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="<?= site_url('users/edit/' . $u->id) ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <div class="modal-body p-4 text-left">
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Username</label>
                                                    <input type="text" class="form-control" value="<?= esc($u->username) ?>" disabled>
                                                    <small class="form-text text-muted">Username tidak dapat diubah.</small>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Nama Lengkap</label>
                                                    <input type="text" name="nama_lengkap" class="form-control" value="<?= esc($u->nama_lengkap) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Password Baru</label>
                                                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Hak Akses / Role</label>
                                                    <select name="role" class="form-control">
                                                        <option value="admin" <?= $u->role === 'admin' ? 'selected' : '' ?>>Administrator / Operator Sekolah</option>
                                                        <option value="guru" <?= $u->role === 'guru' ? 'selected' : '' ?>>Guru Pembimbing</option>
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

<!-- Modal Tambah User -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-user-plus mr-2"></i> Tambah Pengguna Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('users/tambah') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Contoh: operator1" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap petugas" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password kuat" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Hak Akses / Role</label>
                        <select name="role" class="form-control">
                            <option value="admin" selected>Administrator / Operator Sekolah</option>
                            <option value="guru">Guru Pembimbing</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>
