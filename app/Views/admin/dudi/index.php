<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Data Mitra DUDI & Kerjasama MoU</h1>
        <p class="text-muted text-sm mb-0">Kelola direktori Dunia Usaha / Dunia Industri tempat pelaksanaan PKL siswa beserta nomor registrasi MoU Dapodik.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2">
        <a href="<?= site_url('dapodik/tarik_dudi') ?>" class="btn btn-outline-success shadow-sm mr-2 mb-2" onclick="return confirm('Tarik pembaruan data DUDI & MoU dari Dapodik?');">
            <i class="fas fa-sync-alt mr-1"></i> Tarik dari Dapodik
        </a>
        <button type="button" class="btn btn-outline-primary shadow-sm mr-2 mb-2" data-toggle="modal" data-target="#modalImportDudi">
            <i class="fas fa-file-excel mr-1"></i> Impor Excel
        </button>
        <button type="button" class="btn btn-primary shadow-sm mb-2" data-toggle="modal" data-target="#modalTambahDudi">
            <i class="fas fa-plus-circle mr-1"></i> Tambah Mitra DUDI
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
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-building mr-2"></i> Direktori Mitra DUDI & MoU</h6>
        <span class="badge badge-light border text-xs"><?= count($dudi) ?> Mitra Terdata</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Nama Mitra DUDI</th>
                        <th>Bidang Usaha</th>
                        <th>Nomor MoU / Judul PKS</th>
                        <th>PIC Perusahaan</th>
                        <th class="text-center" style="width: 120px;">Status</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dudi)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data mitra DUDI. Silakan tambah data, impor Excel, atau tarik dari Dapodik.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($dudi as $d): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="font-weight-bold text-gray-900"><?= esc($d->nama_dudi) ?></div>
                                    <div class="text-xs text-muted"><?= esc($d->alamat ?: '-') ?></div>
                                    <?php if (!empty($d->latitude) && !empty($d->longitude)): ?>
                                        <div class="text-xs text-success font-weight-bold mt-1">
                                            <i class="fas fa-map-marker-alt mr-1"></i> GPS: <?= esc($d->latitude) ?>, <?= esc($d->longitude) ?> 
                                            <span class="badge badge-light border text-dark ml-1">Radius <?= esc($d->radius_meter ?? 100) ?>m</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-xs text-muted font-italic mt-1">
                                            <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i> Koordinat GPS belum diset
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-light border"><?= esc($d->bidang_usaha ?: 'Umum / Industri') ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($d->nomor_mou)): ?>
                                        <div class="text-xs font-weight-bold text-primary"><i class="fas fa-file-contract mr-1"></i> <?= esc($d->nomor_mou) ?></div>
                                    <?php endif; ?>
                                    <div class="text-xs text-secondary"><?= esc($d->judul_pks ?: '-') ?></div>
                                </td>
                                <td>
                                    <div class="text-sm font-weight-bold text-gray-800"><?= esc($d->nama_pic ?: '-') ?></div>
                                    <div class="text-xs text-muted"><?= esc($d->no_hp_pic ?: '') ?></div>
                                </td>
                                <td class="text-center">
                                    <?php if ($d->status_kerjasama === 'aktif'): ?>
                                        <span class="badge badge-pill badge-success px-3 py-1">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-pill badge-secondary px-3 py-1">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-warning mr-1 shadow-sm" data-toggle="modal" data-target="#modalEditDudi<?= $d->id ?>" title="Edit DUDI">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?= site_url('dudi/hapus/' . $d->id) ?>" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('Hapus mitra DUDI <?= esc($d->nama_dudi) ?>?');" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal Edit DUDI -->
                            <div class="modal fade" id="modalEditDudi<?= $d->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-warning text-white">
                                            <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Data Mitra DUDI</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="<?= site_url('dudi/edit/' . $d->id) ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <div class="modal-body p-4 text-left">
                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label class="font-weight-bold text-gray-700">Nama Perusahaan / DUDI</label>
                                                        <input type="text" name="nama_dudi" class="form-control" value="<?= esc($d->nama_dudi) ?>" required>
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label class="font-weight-bold text-gray-700">Bidang Usaha</label>
                                                        <input type="text" name="bidang_usaha" class="form-control" value="<?= esc($d->bidang_usaha) ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Alamat Perusahaan</label>
                                                    <textarea name="alamat" class="form-control" rows="2"><?= esc($d->alamat) ?></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label class="font-weight-bold text-gray-700">Nomor MoU (Dapodik)</label>
                                                        <input type="text" name="nomor_mou" class="form-control" value="<?= esc($d->nomor_mou) ?>" placeholder="Contoh: 421.5/028/SMK/2024">
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label class="font-weight-bold text-gray-700">Judul Kerjasama / PKS</label>
                                                        <input type="text" name="judul_pks" class="form-control" value="<?= esc($d->judul_pks) ?>" placeholder="Perjanjian Kerjasama PKL Siswa">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label class="font-weight-bold text-gray-700">Nama PIC Perusahaan</label>
                                                        <input type="text" name="nama_pic" class="form-control" value="<?= esc($d->nama_pic) ?>">
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label class="font-weight-bold text-gray-700">No. WhatsApp / HP PIC</label>
                                                        <input type="text" name="no_hp_pic" class="form-control" value="<?= esc($d->no_hp_pic) ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-gray-700">Status Kerjasama</label>
                                                    <select name="status_kerjasama" class="form-control">
                                                        <option value="aktif" <?= $d->status_kerjasama === 'aktif' ? 'selected' : '' ?>>Aktif Bekerjasama</option>
                                                        <option value="nonaktif" <?= $d->status_kerjasama === 'nonaktif' ? 'selected' : '' ?>>Nonaktif / Selesai</option>
                                                    </select>
                                                </div>

                                                <hr class="my-3">
                                                <h6 class="font-weight-bold text-gray-900 text-sm mb-2">
                                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i> Titik Koordinat Presensi Geofencing (Siswa PKL)
                                                </h6>
                                                <div class="row">
                                                    <div class="col-md-5 form-group mb-2">
                                                        <label class="text-xs font-weight-bold text-gray-700">Latitude</label>
                                                        <input type="text" name="latitude" class="form-control form-control-sm" value="<?= esc($d->latitude) ?>" placeholder="-6.89271">
                                                    </div>
                                                    <div class="col-md-5 form-group mb-2">
                                                        <label class="text-xs font-weight-bold text-gray-700">Longitude</label>
                                                        <input type="text" name="longitude" class="form-control form-control-sm" value="<?= esc($d->longitude) ?>" placeholder="108.48392">
                                                    </div>
                                                    <div class="col-md-2 form-group mb-2">
                                                        <label class="text-xs font-weight-bold text-gray-700">Radius (m)</label>
                                                        <input type="number" name="radius_meter" class="form-control form-control-sm" value="<?= esc($d->radius_meter ?? 100) ?>" min="20" max="5000">
                                                    </div>
                                                </div>
                                                <small class="text-muted d-block mt-1">Batas maksimal jarak siswa melakukan selfie absensi (standar 100 meter dari titik DUDI).</small>
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

<!-- Modal Tambah DUDI -->
<div class="modal fade" id="modalTambahDudi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i> Tambah Mitra DUDI</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('dudi/tambah') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-gray-700">Nama Perusahaan / DUDI</label>
                            <input type="text" name="nama_dudi" class="form-control" placeholder="Contoh: PT. Telekomunikasi Indonesia" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-gray-700">Bidang Usaha</label>
                            <input type="text" name="bidang_usaha" class="form-control" placeholder="Contoh: IT & Telekomunikasi">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Alamat Perusahaan</label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat kantor / workshop industri"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-gray-700">Nomor MoU (Dapodik)</label>
                            <input type="text" name="nomor_mou" class="form-control" placeholder="Contoh: 421.5/028/SMK/2024">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-gray-700">Judul Kerjasama / PKS</label>
                            <input type="text" name="judul_pks" class="form-control" placeholder="Kerjasama Pelaksanaan PKL Siswa">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-gray-700">Nama PIC Perusahaan</label>
                            <input type="text" name="nama_pic" class="form-control" placeholder="Nama pembimbing industri / HRD">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-gray-700">No. WhatsApp / HP PIC</label>
                            <input type="text" name="no_hp_pic" class="form-control" placeholder="08XXXXXXXXXX">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Status Kerjasama</label>
                        <select name="status_kerjasama" class="form-control">
                            <option value="aktif" selected>Aktif Bekerjasama</option>
                            <option value="nonaktif">Nonaktif / Selesai</option>
                        </select>
                    </div>

                    <hr class="my-3">
                    <h6 class="font-weight-bold text-gray-900 text-sm mb-2">
                        <i class="fas fa-map-marker-alt text-danger mr-1"></i> Titik Koordinat Presensi Geofencing (Siswa PKL)
                    </h6>
                    <div class="row">
                        <div class="col-md-5 form-group mb-2">
                            <label class="text-xs font-weight-bold text-gray-700">Latitude</label>
                            <input type="text" name="latitude" class="form-control form-control-sm" placeholder="-6.89271">
                        </div>
                        <div class="col-md-5 form-group mb-2">
                            <label class="text-xs font-weight-bold text-gray-700">Longitude</label>
                            <input type="text" name="longitude" class="form-control form-control-sm" placeholder="108.48392">
                        </div>
                        <div class="col-md-2 form-group mb-2">
                            <label class="text-xs font-weight-bold text-gray-700">Radius (m)</label>
                            <input type="number" name="radius_meter" class="form-control form-control-sm" value="100" min="20" max="5000">
                        </div>
                    </div>
                    <small class="text-muted d-block mt-1">Batas maksimal jarak siswa melakukan selfie absensi (standar 100 meter dari titik DUDI).</small>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan Mitra DUDI</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import Excel DUDI -->
<div class="modal fade" id="modalImportDudi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-file-excel mr-2"></i> Impor Data DUDI dari Excel</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('dudi/import_excel') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Pilih Berkas Excel (.xlsx)</label>
                        <div class="custom-file">
                            <input type="file" name="file_excel" class="custom-file-input" id="fileExcelDudi" accept=".xlsx" required>
                            <label class="custom-file-label" for="fileExcelDudi">Pilih berkas Excel...</label>
                        </div>
                    </div>
                    <div class="p-3 bg-light rounded border text-xs">
                        <strong>Format Kolom Excel:</strong><br>
                        Kolom A: Nama DUDI | Kolom B: Bidang Usaha | Kolom C: Alamat | Kolom D: No MoU | Kolom E: Judul PKS<br>
                        <div class="mt-2">
                            <a href="<?= site_url('dudi/download_template') ?>" class="text-primary font-weight-bold">
                                <i class="fas fa-download mr-1"></i> Unduh Contoh Format Excel DUDI
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success font-weight-bold"><i class="fas fa-upload mr-1"></i> Mulai Impor DUDI</button>
                </div>
            </form>
        </div>
    </div>
</div>
