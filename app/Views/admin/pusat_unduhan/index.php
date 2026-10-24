<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Pusat Unduhan & Format Berkas</h1>
        <p class="text-muted text-sm mb-0">Kelola dan unduh template berkas Excel, instrumen penilaian, dan panduan PKL untuk operator dan guru.</p>
    </div>
    <div class="mt-3 mt-md-0">
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modalUploadBerkas">
            <i class="fas fa-upload mr-1"></i> Unggah Berkas Baru
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

<!-- Quick Template Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100 border-left-success">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="font-weight-bold text-gray-900 mb-1">Template Siswa</h6>
                    <p class="text-muted text-xs mb-0">Format import data siswa PKL</p>
                </div>
                <a href="<?= site_url('siswa/download_template') ?>" class="btn btn-sm btn-outline-success font-weight-bold">
                    <i class="fas fa-download mr-1"></i> Unduh
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100 border-left-primary">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="font-weight-bold text-gray-900 mb-1">Template Guru</h6>
                    <p class="text-muted text-xs mb-0">Format import data guru & NIP</p>
                </div>
                <a href="<?= site_url('guru/download_template') ?>" class="btn btn-sm btn-outline-primary font-weight-bold">
                    <i class="fas fa-download mr-1"></i> Unduh
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100 border-left-warning">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="font-weight-bold text-gray-900 mb-1">Template DUDI</h6>
                    <p class="text-muted text-xs mb-0">Format import mitra & MoU</p>
                </div>
                <a href="<?= site_url('dudi/download_template') ?>" class="btn btn-sm btn-outline-warning font-weight-bold">
                    <i class="fas fa-download mr-1"></i> Unduh
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-folder-open mr-2"></i> Arsip Dokumen & Berkas Terunggah</h6>
        <span class="badge badge-light border text-xs"><?= count($unduhan) ?> Berkas</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Judul Dokumen / Panduan</th>
                        <th>Kategori</th>
                        <th style="width: 120px;">Ukuran File</th>
                        <th class="text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($unduhan)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada dokumen yang diunggah.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($unduhan as $u): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="font-weight-bold text-gray-900"><?= esc($u->judul) ?></div>
                                    <div class="text-xs text-muted"><?= esc($u->file) ?></div>
                                </td>
                                <td>
                                    <span class="badge badge-light border"><?= esc($u->kategori ?: 'Umum') ?></span>
                                </td>
                                <td><?= esc($u->ukuran_file ?: '-') ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('uploads/unduhan/' . $u->file) ?>" download class="btn btn-sm btn-outline-primary mr-1 shadow-sm">
                                        <i class="fas fa-download mr-1"></i> Unduh
                                    </a>
                                    <a href="<?= site_url('pusatunduhan/hapus/' . $u->id) ?>" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('Hapus berkas <?= esc($u->judul) ?>?');" title="Hapus">
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

<!-- Modal Upload Berkas -->
<div class="modal fade" id="modalUploadBerkas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-upload mr-2"></i> Unggah Berkas Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('pusatunduhan/tambah') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Judul Dokumen</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Instrumen Monitoring PKL 2024" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Kategori</label>
                        <select name="kategori" class="form-control">
                            <option value="Template">Template Excel</option>
                            <option value="Panduan">Panduan & Juknis</option>
                            <option value="Instrumen">Instrumen Penilaian</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Pilih Berkas</label>
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="uploadFile" required>
                            <label class="custom-file-label" for="uploadFile">Pilih file...</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save mr-1"></i> Unggah Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>
