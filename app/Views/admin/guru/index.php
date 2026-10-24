<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Data Guru / Tenaga Pendidik</h1>
        <p class="text-muted text-sm mb-0">Kelola daftar guru pembimbing PKL, NIP Dapodik, dan bidang keahlian produktif.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2">
        <a href="<?= site_url('dapodik/tarik_guru') ?>" class="btn btn-outline-success shadow-sm mr-2 mb-2" onclick="return confirm('Tarik pembaruan data guru dari Dapodik?');">
            <i class="fas fa-sync-alt mr-1"></i> Tarik dari Dapodik
        </a>
        <button type="button" class="btn btn-outline-primary shadow-sm mr-2 mb-2" data-toggle="modal" data-target="#modalImportGuru">
            <i class="fas fa-file-excel mr-1"></i> Impor Excel
        </button>
        <button type="button" class="btn btn-primary shadow-sm mb-2" data-toggle="modal" data-target="#modalTambahGuru">
            <i class="fas fa-user-plus mr-1"></i> Tambah Guru
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
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-tie mr-2"></i> Daftar Guru Pembimbing PKL</h6>
        <span class="badge badge-light border text-xs"><?= count($guru) ?> Guru Terdaftar</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 60px;" class="text-center">No</th>
                        <th>Nama Lengkap Guru</th>
                        <th style="width: 200px;">NIP</th>
                        <th>Kompetensi Keahlian</th>
                        <th class="text-center" style="width: 140px;">Kategori</th>
                        <th class="text-center" style="width: 120px;">Status</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($guru)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data guru. Silakan tambah guru, impor Excel, atau tarik dari Dapodik.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($guru as $g): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="font-weight-bold text-gray-900"><?= esc($g->nama_guru) ?></div>
                                </td>
                                <td>
                                    <span class="text-monospace text-secondary"><?= esc($g->nip ?: '-') ?></span>
                                </td>
                                <td>
                                    <?= esc($g->nama_jurusan ?: 'Semua Jurusan') ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($g->jenis_guru === 'produktif'): ?>
                                        <span class="badge badge-info px-2 py-1">Produktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary px-2 py-1">Umum / Adaptif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($g->status === 'aktif'): ?>
                                        <span class="badge badge-pill badge-success px-3 py-1">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-pill badge-secondary px-3 py-1">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-warning mr-1 shadow-sm" data-toggle="modal" data-target="#modalEditGuru<?= $g->id ?>" title="Edit Guru">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?= site_url('guru/hapus/' . $g->id) ?>" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('Hapus data guru <?= esc($g->nama_guru) ?>?');" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal Edit Guru -->
                            <div class="modal fade" id="modalEditGuru<?= $g->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-warning text-white">
                                            <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Data Guru</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="<?= site_url('guru/edit/' . $g->id) ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <div class="modal-body p-4 text-left">
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Nama Guru (dengan gelar)</label>
                                                    <input type="text" name="nama_guru" class="form-control" value="<?= esc($g->nama_guru) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">NIP</label>
                                                    <input type="text" name="nip" class="form-control" value="<?= esc($g->nip) ?>" placeholder="19XXXXXXXXXXXXXX atau tanda strip (-)">
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Kompetensi Keahlian</label>
                                                    <select name="jurusan_id" class="form-control">
                                                        <option value="0">Semua Jurusan</option>
                                                        <?php foreach ($jurusan as $j): ?>
                                                            <option value="<?= $j->id ?>" <?= $g->jurusan_id == $j->id ? 'selected' : '' ?>>
                                                                <?= esc($j->nama_jurusan) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Kategori Guru</label>
                                                    <select name="jenis_guru" class="form-control">
                                                        <option value="produktif" <?= $g->jenis_guru === 'produktif' ? 'selected' : '' ?>>Guru Produktif Kejuruan</option>
                                                        <option value="umum" <?= $g->jenis_guru === 'umum' ? 'selected' : '' ?>>Guru Umum / Normatif / Adaptif</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Status</label>
                                                    <select name="status" class="form-control">
                                                        <option value="aktif" <?= $g->status === 'aktif' ? 'selected' : '' ?>>Aktif Mengajar</option>
                                                        <option value="nonaktif" <?= $g->status === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
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

<!-- Modal Tambah Guru -->
<div class="modal fade" id="modalTambahGuru" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-user-plus mr-2"></i> Tambah Data Guru</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('guru/tambah') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Nama Guru (dengan gelar)</label>
                        <input type="text" name="nama_guru" class="form-control" placeholder="Contoh: Budi Santoso, S.Kom., M.T." required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">NIP</label>
                        <input type="text" name="nip" class="form-control" placeholder="19XXXXXXXXXXXXXX atau tanda strip (-)">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Kompetensi Keahlian</label>
                        <select name="jurusan_id" class="form-control">
                            <option value="0">Semua Jurusan</option>
                            <?php foreach ($jurusan as $j): ?>
                                <option value="<?= $j->id ?>"><?= esc($j->nama_jurusan) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Kategori Guru</label>
                        <select name="jenis_guru" class="form-control">
                            <option value="produktif" selected>Guru Produktif Kejuruan</option>
                            <option value="umum">Guru Umum / Normatif / Adaptif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Status</label>
                        <select name="status" class="form-control">
                            <option value="aktif" selected>Aktif Mengajar</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan Data Guru</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import Excel Guru -->
<div class="modal fade" id="modalImportGuru" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-file-excel mr-2"></i> Impor Data Guru dari Excel</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('guru/import_excel') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Pilih Berkas Excel (.xlsx)</label>
                        <div class="custom-file">
                            <input type="file" name="file_excel" class="custom-file-input" id="fileExcelGuru" accept=".xlsx" required>
                            <label class="custom-file-label" for="fileExcelGuru">Pilih berkas Excel...</label>
                        </div>
                    </div>
                    <div class="p-3 bg-light rounded border text-xs">
                        <strong>Format Kolom Excel:</strong><br>
                        Kolom A: Nama Guru | Kolom B: NIP | Kolom C: Jenis (produktif/umum)<br>
                        <div class="mt-2">
                            <a href="<?= site_url('guru/download_template') ?>" class="text-primary font-weight-bold">
                                <i class="fas fa-download mr-1"></i> Unduh Contoh Format Excel
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success font-weight-bold"><i class="fas fa-upload mr-1"></i> Mulai Impor Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
