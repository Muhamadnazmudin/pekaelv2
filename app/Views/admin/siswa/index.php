<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Data Siswa Peserta PKL</h1>
        <p class="text-muted text-sm mb-0">Kelola data siswa tingkat akhir peserta Praktik Kerja Lapangan dan penempatan mitra DUDI.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2">
        <a href="<?= site_url('dapodik/tarik_siswa') ?>" class="btn btn-outline-success shadow-sm mr-2 mb-2" onclick="return confirm('Tarik pembaruan data siswa rombel PKL dari Dapodik?');">
            <i class="fas fa-sync-alt mr-1"></i> Tarik dari Dapodik
        </a>
        <button type="button" class="btn btn-outline-primary shadow-sm mr-2 mb-2" data-toggle="modal" data-target="#modalImportSiswa">
            <i class="fas fa-file-excel mr-1"></i> Impor Excel
        </button>
        <button type="button" class="btn btn-primary shadow-sm mb-2" data-toggle="modal" data-target="#modalTambahSiswa">
            <i class="fas fa-user-plus mr-1"></i> Tambah Siswa
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

<!-- Filter Card -->
<div class="card shadow-sm mb-4 border-0 bg-white">
    <div class="card-body p-3">
        <form method="GET" action="<?= site_url('siswa') ?>" class="row align-items-center g-2">
            <div class="col-md-4 mb-2 mb-md-0">
                <label class="text-xs text-muted font-weight-bold text-uppercase mb-1">Tahun Pelajaran</label>
                <select name="tahun_id" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Tahun Pelajaran --</option>
                    <?php foreach ($tahun_list as $t): ?>
                        <option value="<?= $t->id ?>" <?= $tahun_pilih == $t->id ? 'selected' : '' ?>>
                            <?= esc($t->tahun) ?> <?= $t->status === 'aktif' ? '(Aktif)' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 mb-2 mb-md-0">
                <label class="text-xs text-muted font-weight-bold text-uppercase mb-1">Filter Rombel / Kelas</label>
                <select name="kelas_id" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    <?php foreach ($kelas as $k): ?>
                        <option value="<?= $k->id ?>" <?= $kelas_pilih == $k->id ? 'selected' : '' ?>>
                            <?= esc($k->nama_kelas) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="<?= site_url('siswa') ?>" class="btn btn-sm btn-light border text-muted">
                    <i class="fas fa-undo mr-1"></i> Reset Filter
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-graduate mr-2"></i> Daftar Siswa Peserta PKL</h6>
        <span class="badge badge-light border text-xs"><?= count($siswa) ?> Siswa Ditemukan</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th style="width: 140px;">NISN</th>
                        <th>Nama Lengkap Siswa</th>
                        <th style="width: 70px;" class="text-center">L/P</th>
                        <th>Kelas / Rombel</th>
                        <th>Mitra DUDI Tempat PKL</th>
                        <th class="text-center" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($siswa)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data siswa untuk filter yang dipilih. Silakan tambah data, impor Excel, atau tarik dari Dapodik.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($siswa as $s): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <span class="text-monospace font-weight-bold text-primary"><?= esc($s->nisn ?: '-') ?></span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-gray-900"><?= esc($s->nama) ?></div>
                                </td>
                                <td class="text-center font-weight-bold">
                                    <span class="badge badge-light border <?= $s->jenis_kelamin === 'L' ? 'text-primary' : 'text-danger' ?>">
                                        <?= esc($s->jenis_kelamin ?: 'L') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-info px-2 py-1"><?= esc($s->nama_kelas ?: '-') ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($s->nama_dudi)): ?>
                                        <span class="font-weight-bold text-gray-800"><i class="fas fa-building text-muted mr-1"></i> <?= esc($s->nama_dudi) ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> Belum Ditempatkan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-warning mr-1 shadow-sm" data-toggle="modal" data-target="#modalEditSiswa<?= $s->id ?>" title="Edit Siswa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?= site_url('siswa/hapus/' . $s->id) ?>" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('Hapus data siswa <?= esc($s->nama) ?>?');" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal Edit Siswa -->
                            <div class="modal fade" id="modalEditSiswa<?= $s->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-warning text-white">
                                            <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Data Siswa</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="<?= site_url('siswa/edit/' . $s->id) ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <div class="modal-body p-4 text-left">
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">NISN (10 Digit)</label>
                                                    <input type="text" name="nisn" class="form-control" value="<?= esc($s->nisn) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Nama Lengkap Siswa</label>
                                                    <input type="text" name="nama" class="form-control" value="<?= esc($s->nama) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Jenis Kelamin</label>
                                                    <select name="jenis_kelamin" class="form-control">
                                                        <option value="L" <?= $s->jenis_kelamin === 'L' ? 'selected' : '' ?>>Laki-laki (L)</option>
                                                        <option value="P" <?= $s->jenis_kelamin === 'P' ? 'selected' : '' ?>>Perempuan (P)</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Kelas / Rombel</label>
                                                    <select name="id_kelas" class="form-control" required>
                                                        <?php foreach ($kelas as $k): ?>
                                                            <option value="<?= $k->id ?>" <?= $s->id_kelas == $k->id ? 'selected' : '' ?>>
                                                                <?= esc($k->nama_kelas) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Tahun Pelajaran</label>
                                                    <select name="id_tahun" class="form-control" required>
                                                        <?php foreach ($tahun_list as $t): ?>
                                                            <option value="<?= $t->id ?>" <?= $s->id_tahun == $t->id ? 'selected' : '' ?>>
                                                                <?= esc($t->tahun) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Mitra DUDI Tempat PKL</label>
                                                    <select name="dudi_id" class="form-control">
                                                        <option value="0">-- Belum Ditentukan --</option>
                                                        <?php foreach ($dudi as $du): ?>
                                                            <option value="<?= $du->id ?>" <?= $s->dudi_id == $du->id ? 'selected' : '' ?>>
                                                                <?= esc($du->nama_dudi) ?>
                                                            </option>
                                                        <?php endforeach; ?>
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

<!-- Modal Tambah Siswa -->
<div class="modal fade" id="modalTambahSiswa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-user-plus mr-2"></i> Tambah Siswa PKL</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('siswa/tambah') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">NISN (10 Digit)</label>
                        <input type="text" name="nisn" class="form-control" placeholder="Nomor Induk Siswa Nasional" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Nama Lengkap Siswa</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama lengkap siswa" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="L">Laki-laki (L)</option>
                            <option value="P">Perempuan (P)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Kelas / Rombel</label>
                        <select name="id_kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelas as $k): ?>
                                <option value="<?= $k->id ?>"><?= esc($k->nama_kelas) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Tahun Pelajaran</label>
                        <select name="id_tahun" class="form-control" required>
                            <?php foreach ($tahun_list as $t): ?>
                                <option value="<?= $t->id ?>" <?= $t->status === 'aktif' ? 'selected' : '' ?>><?= esc($t->tahun) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Mitra DUDI Tempat PKL</label>
                        <select name="dudi_id" class="form-control">
                            <option value="0">-- Belum Ditentukan (Bisa diatur di Mapping DUDI) --</option>
                            <?php foreach ($dudi as $du): ?>
                                <option value="<?= $du->id ?>"><?= esc($du->nama_dudi) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan Data Siswa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import Excel Siswa -->
<div class="modal fade" id="modalImportSiswa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-file-excel mr-2"></i> Impor Data Siswa dari Excel</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('siswa/import_excel') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Pilih Kelas Sasaran</label>
                        <select name="id_kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelas as $k): ?>
                                <option value="<?= $k->id ?>"><?= esc($k->nama_kelas) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Pilih Tahun Pelajaran</label>
                        <select name="id_tahun" class="form-control" required>
                            <?php foreach ($tahun_list as $t): ?>
                                <option value="<?= $t->id ?>" <?= $t->status === 'aktif' ? 'selected' : '' ?>><?= esc($t->tahun) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Pilih Berkas Excel (.xlsx)</label>
                        <div class="custom-file">
                            <input type="file" name="file_excel" class="custom-file-input" id="fileExcelSiswa" accept=".xlsx" required>
                            <label class="custom-file-label" for="fileExcelSiswa">Pilih berkas Excel...</label>
                        </div>
                    </div>
                    <div class="p-3 bg-light rounded border text-xs">
                        <strong>Format Kolom Excel:</strong><br>
                        Kolom A: NISN | Kolom B: Nama Lengkap | Kolom C: Jenis Kelamin (L/P)<br>
                        <div class="mt-2">
                            <a href="<?= site_url('siswa/download_template') ?>" class="text-primary font-weight-bold">
                                <i class="fas fa-download mr-1"></i> Unduh Contoh Format Excel Siswa
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success font-weight-bold"><i class="fas fa-upload mr-1"></i> Mulai Impor Siswa</button>
                </div>
            </form>
        </div>
    </div>
</div>
