<?= $this->extend('portal_siswa/layout') ?>

<?= $this->section('content') ?>

<!-- Page Heading & Add Action -->
<div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-4">
    <div>
        <h4 class="font-weight-800 text-gray-900 mb-1" style="letter-spacing: -0.02em;">
            <i class="fas fa-book-open text-primary mr-2"></i> Laporan &amp; Jurnal Harian PKL
        </h4>
        <p class="text-muted text-xs mb-0">Catat aktivitas pembelajaran, tugas, dan dokumentasi praktik kerja harian Anda untuk diperiksa guru pembimbing.</p>
    </div>
    <div class="mt-3 mt-sm-0">
        <button type="button" class="btn btn-primary font-weight-bold shadow-sm px-3.5 py-2 rounded-xl d-flex align-items-center" data-toggle="modal" data-target="#modalTambahJurnal">
            <i class="fas fa-plus-circle mr-1.5"></i> Tulis Jurnal Hari Ini
        </button>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-xl mb-4" role="alert" style="background-color: #ecfdf5; color: #065f46; border-left: 4px solid #10b981 !important;">
        <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-xl mb-4" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">
        <i class="fas fa-exclamation-triangle mr-2"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<!-- Modal Tambah Jurnal -->
<div class="modal fade" id="modalTambahJurnal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg rounded-2xl">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Tulis Laporan Jurnal Harian PKL</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('siswa/jurnal_simpan') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4 text-left">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-gray-700 text-xs text-uppercase">Tanggal Kegiatan *</label>
                            <input type="date" name="tanggal" class="form-control font-weight-bold" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-gray-700 text-xs text-uppercase">Guru Pembimbing Sekolah</label>
                            <input type="text" class="form-control font-weight-bold bg-light text-muted" value="<?= esc($siswa->nama_pembimbing ?: 'Menunggu Alokasi Pembimbing') ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-gray-700 text-xs text-uppercase">Uraian Aktivitas / Pekerjaan yang Dilakukan *</label>
                        <textarea name="kegiatan" class="form-control" rows="4" placeholder="Jelaskan secara rinci tugas, proyek, perbaikan, atau kegiatan praktis yang Anda kerjakan di tempat PKL hari ini..." required></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-gray-700 text-xs text-uppercase">Hasil Pembelajaran / Kompetensi yang Didapat</label>
                        <textarea name="hasil" class="form-control" rows="2" placeholder="Keterampilan teknis baru apa yang Anda kuasai hari ini? (Misal: perakitan komponen, instalasi software, pelayanan kasir, dsb.)"></textarea>
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-weight-bold text-gray-700 text-xs text-uppercase"><i class="fas fa-camera text-primary mr-1"></i> Foto Dokumentasi Kegiatan (Opsional)</label>
                        <div class="custom-file">
                            <input type="file" name="foto_kegiatan" class="custom-file-input" id="fileFotoKegiatan" accept="image/*">
                            <label class="custom-file-label" for="fileFotoKegiatan">Pilih foto dokumentasi praktik...</label>
                        </div>
                        <small class="form-text text-muted">Format: JPG, PNG, atau WEBP. Maksimal 3MB.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-lg" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold rounded-lg shadow-sm">
                        <i class="fas fa-paper-plane mr-1"></i> Kirim Laporan ke Pembimbing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- List of Submitted Journals -->
<div class="card border-0 shadow-sm rounded-xl overflow-hidden">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-gray-900">
            <i class="fas fa-list-alt text-primary mr-2"></i> Arsip Laporan Kegiatan Anda
        </h6>
        <span class="badge badge-light border text-xs"><?= count($daftarJurnal) ?> Total Jurnal</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($daftarJurnal)): ?>
            <div class="text-center py-5">
                <div class="rounded-circle bg-light d-inline-flex p-4 text-muted mb-3">
                    <i class="fas fa-book fa-3x"></i>
                </div>
                <h6 class="font-weight-bold text-gray-800">Belum Ada Jurnal Tersimpan</h6>
                <p class="text-muted text-xs mb-3">Catat setiap kegiatan praktik Anda agar guru pembimbing dapat memantau dan memberikan evaluasi nilai.</p>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold rounded-lg shadow-sm" data-toggle="modal" data-target="#modalTambahJurnal">
                    <i class="fas fa-plus mr-1"></i> Tulis Jurnal Pertama Anda
                </button>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 130px;">Tanggal</th>
                            <th style="width: 80px;" class="text-center">Foto</th>
                            <th>Aktivitas &amp; Pembelajaran</th>
                            <th style="width: 170px;">Status Verifikasi</th>
                            <th style="width: 140px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daftarJurnal as $j): ?>
                            <tr>
                                <td>
                                    <div class="font-weight-bold text-gray-900 text-sm">
                                        <?= date('d M Y', strtotime($j->tanggal)) ?>
                                    </div>
                                    <span class="text-xs text-muted"><?= date('l', strtotime($j->tanggal)) ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($j->foto)): ?>
                                        <a href="<?= base_url('uploads/jurnal/' . $j->foto) ?>" target="_blank" title="Klik untuk memperbesar foto">
                                            <img src="<?= base_url('uploads/jurnal/' . $j->foto) ?>" class="rounded-lg shadow-sm border" style="width: 52px; height: 52px; object-fit: cover;">
                                        </a>
                                    <?php else: ?>
                                        <span class="badge badge-light border text-muted text-xs">Tanpa Foto</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-gray-900 text-sm mb-1">
                                        <?= nl2br(esc($j->kegiatan)) ?>
                                    </div>
                                    <?php if (!empty($j->hasil)): ?>
                                        <div class="p-2 bg-light rounded border text-xs text-secondary mt-1">
                                            <strong>Kompetensi/Hasil:</strong> <?= esc($j->hasil) ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Catatan Pembimbing jika ada -->
                                    <?php if (!empty($j->catatan_pembimbing)): ?>
                                        <div class="p-2.5 rounded-lg mt-2 text-xs" style="background-color: <?= ($j->status_verifikasi === 'revisi') ? '#fff1f2' : '#f0fdf4' ?>; border: 1px solid <?= ($j->status_verifikasi === 'revisi') ? '#fecdd3' : '#bbf7d0' ?>; color: <?= ($j->status_verifikasi === 'revisi') ? '#9f1239' : '#166534' ?>;">
                                            <div class="font-weight-bold mb-0.5">
                                                <i class="fas fa-comment-dots mr-1"></i> Catatan Guru Pembimbing (<?= esc($j->nama_pembimbing ?: 'Pembimbing') ?>):
                                            </div>
                                            <div><?= esc($j->catatan_pembimbing) ?></div>
                                            <?php if ($j->status_verifikasi === 'revisi'): ?>
                                                <div class="mt-1 font-weight-bold text-danger">
                                                    <i class="fas fa-arrow-right mr-1"></i> Silakan klik tombol "Edit" di samping untuk memperbaiki laporan Anda.
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($j->status_verifikasi === 'disetujui'): ?>
                                        <span class="badge badge-success px-2.5 py-1 text-xs font-weight-bold d-inline-flex align-items-center">
                                            <i class="fas fa-check-circle mr-1"></i> Disetujui
                                        </span>
                                        <div class="text-xs text-muted mt-1"><i class="fas fa-lock text-success mr-1"></i> Laporan Terkunci</div>
                                    <?php elseif ($j->status_verifikasi === 'revisi'): ?>
                                        <span class="badge badge-danger px-2.5 py-1 text-xs font-weight-bold d-inline-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Perlu Revisi
                                        </span>
                                        <div class="text-xs text-danger mt-1">Dapat diedit ulang</div>
                                    <?php else: ?>
                                        <span class="badge badge-warning text-dark px-2.5 py-1 text-xs font-weight-bold d-inline-flex align-items-center">
                                            <i class="fas fa-clock mr-1"></i> Menunggu Guru
                                        </span>
                                        <div class="text-xs text-muted mt-1">Belum diverifikasi</div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($j->status_verifikasi === 'disetujui'): ?>
                                        <span class="badge badge-light border text-muted py-1.5 px-2.5" title="Laporan sudah disetujui pembimbing dan terkunci dari perubahan">
                                            <i class="fas fa-lock mr-1 text-success"></i> Terkunci
                                        </span>
                                    <?php else: ?>
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-warning mr-1 shadow-sm px-2.5 py-1" data-toggle="modal" data-target="#modalEditJurnal<?= $j->id ?>" title="Edit Laporan Jurnal">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </button>
                                            <a href="<?= site_url('siswa/jurnal_hapus/' . $j->id) ?>" class="btn btn-sm btn-outline-danger shadow-sm px-2 py-1" onclick="return confirm('Hapus laporan jurnal tanggal <?= date('d/m/Y', strtotime($j->tanggal)) ?>?');" title="Hapus Jurnal">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Modal Edit Jurnal (Hanya jika belum disetujui) -->
                            <?php if ($j->status_verifikasi !== 'disetujui'): ?>
                                <div class="modal fade" id="modalEditJurnal<?= $j->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content border-0 shadow-lg rounded-2xl">
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Laporan Jurnal Harian</h5>
                                                <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
                                            </div>
                                            <form action="<?= site_url('siswa/jurnal_edit/' . $j->id) ?>" method="POST" enctype="multipart/form-data">
                                                <?= csrf_field() ?>
                                                <div class="modal-body p-4 text-left">
                                                    <?php if ($j->status_verifikasi === 'revisi' && !empty($j->catatan_pembimbing)): ?>
                                                        <div class="alert alert-danger border-0 rounded-xl mb-3 p-3">
                                                            <div class="font-weight-bold text-xs text-uppercase mb-1">
                                                                <i class="fas fa-exclamation-circle mr-1"></i> Catatan Perbaikan dari Guru Pembimbing:
                                                            </div>
                                                            <div class="text-sm"><?= esc($j->catatan_pembimbing) ?></div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="row">
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label class="font-weight-bold text-gray-700 text-xs text-uppercase">Tanggal Kegiatan *</label>
                                                            <input type="date" name="tanggal" class="form-control font-weight-bold" value="<?= esc($j->tanggal) ?>" required>
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label class="font-weight-bold text-gray-700 text-xs text-uppercase">Status Saat Ini</label>
                                                            <input type="text" class="form-control bg-light text-muted font-weight-bold" value="<?= ($j->status_verifikasi === 'revisi') ? 'Perlu Revisi (Akan dikirim ulang)' : 'Menunggu Verifikasi' ?>" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label class="font-weight-bold text-gray-700 text-xs text-uppercase">Uraian Aktivitas / Pekerjaan yang Dilakukan *</label>
                                                        <textarea name="kegiatan" class="form-control" rows="4" required><?= esc($j->kegiatan) ?></textarea>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label class="font-weight-bold text-gray-700 text-xs text-uppercase">Hasil Pembelajaran / Kompetensi yang Didapat</label>
                                                        <textarea name="hasil" class="form-control" rows="2"><?= esc($j->hasil) ?></textarea>
                                                    </div>

                                                    <div class="form-group mb-2">
                                                        <label class="font-weight-bold text-gray-700 text-xs text-uppercase">
                                                            <i class="fas fa-camera text-primary mr-1"></i> Foto Dokumentasi Kegiatan (Opsional)
                                                        </label>
                                                        <?php if (!empty($j->foto)): ?>
                                                            <div class="d-flex align-items-center mb-2 p-2 bg-light rounded border">
                                                                <img src="<?= base_url('uploads/jurnal/' . $j->foto) ?>" class="rounded mr-2 border" style="width: 40px; height: 40px; object-fit: cover;">
                                                                <span class="text-xs text-muted">Foto saat ini terlampir. Pilih file baru di bawah jika ingin mengganti.</span>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="custom-file">
                                                            <input type="file" name="foto_kegiatan" class="custom-file-input" id="editFoto<?= $j->id ?>" accept="image/*">
                                                            <label class="custom-file-label" for="editFoto<?= $j->id ?>">Pilih foto baru...</label>
                                                        </div>
                                                        <small class="form-text text-muted">Format: JPG, PNG, atau WEBP. Maksimal 3MB.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary rounded-lg" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning font-weight-bold text-white rounded-lg shadow-sm">
                                                        <i class="fas fa-save mr-1"></i> Simpan Perubahan Laporan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});
</script>
<?= $this->endSection() ?>
