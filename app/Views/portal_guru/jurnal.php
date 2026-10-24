<?= $this->extend('portal_guru/layout') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h4 class="font-weight-800 text-gray-900 mb-1" style="letter-spacing: -0.02em;">
            <i class="fas fa-book-reader text-primary mr-2"></i> Monitoring &amp; Validasi Jurnal PKL
        </h4>
        <p class="text-muted text-xs mb-0">Tinjau laporan aktivitas harian, periksa dokumentasi foto, dan berikan evaluasi persetujuan kepada siswa bimbingan.</p>
    </div>

    <!-- Filter Buttons -->
    <div class="mt-3 mt-md-0 d-flex flex-wrap" style="gap: 6px;">
        <a href="<?= site_url('guru/jurnal') ?>" class="btn btn-sm <?= empty($statusFilter) ? 'btn-primary' : 'btn-light border' ?> font-weight-bold rounded-pill px-3">
            Semua
        </a>
        <a href="<?= site_url('guru/jurnal?status=menunggu') ?>" class="btn btn-sm <?= $statusFilter === 'menunggu' ? 'btn-warning text-dark font-weight-bold' : 'btn-light border' ?> rounded-pill px-3">
            <i class="fas fa-clock mr-1"></i> Menunggu Verifikasi
        </a>
        <a href="<?= site_url('guru/jurnal?status=disetujui') ?>" class="btn btn-sm <?= $statusFilter === 'disetujui' ? 'btn-success font-weight-bold' : 'btn-light border' ?> rounded-pill px-3">
            <i class="fas fa-check-circle mr-1"></i> Disetujui
        </a>
        <a href="<?= site_url('guru/jurnal?status=revisi') ?>" class="btn btn-sm <?= $statusFilter === 'revisi' ? 'btn-danger font-weight-bold' : 'btn-light border' ?> rounded-pill px-3">
            <i class="fas fa-exclamation-triangle mr-1"></i> Revisi
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-xl overflow-hidden">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-gray-900">
            <i class="fas fa-list-alt text-primary mr-1"></i> Daftar Laporan Jurnal Harian Siswa Bimbingan
        </h6>
        <span class="badge badge-light border text-xs"><?= count($jurnals) ?> Laporan</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($jurnals)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-check-circle fa-3x mb-2 text-success"></i>
                <h6 class="font-weight-bold text-gray-800">Tidak Ada Laporan Jurnal yang Perlu Ditinjau</h6>
                <p class="text-xs mb-0">Semua laporan jurnal harian siswa bimbingan telah selesai diverifikasi atau belum ada laporan baru.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th style="width: 140px;">Tanggal</th>
                            <th>Nama Siswa &amp; Kelas</th>
                            <th style="width: 80px;" class="text-center">Foto</th>
                            <th>Aktivitas yang Dikerjakan</th>
                            <th style="width: 130px;" class="text-center">Status</th>
                            <th style="width: 120px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($jurnals as $j): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="font-weight-bold text-gray-900 text-sm">
                                        <?= date('d/m/Y', strtotime($j->tanggal)) ?>
                                    </div>
                                    <span class="text-xs text-muted"><?= date('l', strtotime($j->tanggal)) ?></span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-gray-900 text-sm"><?= esc($j->nama_siswa) ?></div>
                                    <div class="text-xs text-muted">NISN: <?= esc($j->nisn) ?> &bull; <?= esc($j->nama_kelas) ?></div>
                                    <div class="text-xs text-primary font-weight-600 mt-0.5"><?= esc($j->nama_dudi ?: '-') ?></div>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($j->foto)): ?>
                                        <a href="<?= base_url('uploads/jurnal/' . $j->foto) ?>" target="_blank">
                                            <img src="<?= base_url('uploads/jurnal/' . $j->foto) ?>" class="rounded-lg shadow-sm border" style="width: 48px; height: 48px; object-fit: cover;" title="Klik untuk memperbesar foto">
                                        </a>
                                    <?php else: ?>
                                        <span class="badge badge-light border text-muted text-xs">Tanpa Foto</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-900 font-weight-600 mb-1">
                                        <?= nl2br(esc($j->kegiatan)) ?>
                                    </div>
                                    <?php if (!empty($j->hasil)): ?>
                                        <div class="p-2 bg-light rounded border text-xs text-secondary mt-1">
                                            <strong>Kompetensi Didapat:</strong> <?= esc($j->hasil) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($j->catatan_pembimbing)): ?>
                                        <div class="p-2 rounded mt-1 text-xs" style="background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;">
                                            <i class="fas fa-comment mr-1"></i> <strong>Catatan Anda:</strong> <?= esc($j->catatan_pembimbing) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($j->status_verifikasi === 'disetujui'): ?>
                                        <span class="badge badge-success px-2.5 py-1 text-xs font-weight-bold">
                                            <i class="fas fa-check mr-1"></i> Disetujui
                                        </span>
                                    <?php elseif ($j->status_verifikasi === 'revisi'): ?>
                                        <span class="badge badge-danger px-2.5 py-1 text-xs font-weight-bold">
                                            <i class="fas fa-exclamation mr-1"></i> Revisi
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-warning text-dark px-2.5 py-1 text-xs font-weight-bold">
                                            <i class="fas fa-clock mr-1"></i> Menunggu
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary font-weight-bold shadow-sm rounded-lg px-2.5 py-1" data-toggle="modal" data-target="#modalVerif<?= $j->id ?>" title="Verifikasi / Beri Catatan">
                                        <i class="fas fa-check-circle mr-1"></i> Verifikasi
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Verifikasi Jurnal -->
                            <div class="modal fade" id="modalVerif<?= $j->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow-lg rounded-2xl">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title font-weight-bold"><i class="fas fa-check-double mr-2"></i> Evaluasi Jurnal Siswa</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="<?= site_url('guru/jurnal_verifikasi/' . $j->id) ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <div class="modal-body p-4 text-left">
                                                <div class="mb-3 p-3 bg-light rounded-lg border">
                                                    <div class="font-weight-bold text-gray-900"><?= esc($j->nama_siswa) ?> (<?= esc($j->nama_kelas) ?>)</div>
                                                    <div class="text-xs text-muted mb-2">Tanggal Kegiatan: <?= date('d F Y', strtotime($j->tanggal)) ?></div>
                                                    <div class="text-xs text-secondary mb-1"><strong>Uraian:</strong> <?= esc($j->kegiatan) ?></div>
                                                    <?php if (!empty($j->hasil)): ?>
                                                        <div class="text-xs text-secondary"><strong>Kompetensi:</strong> <?= esc($j->hasil) ?></div>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label class="font-weight-bold text-gray-700 text-xs text-uppercase">Keputusan Verifikasi *</label>
                                                    <select name="status_verifikasi" class="form-control font-weight-bold">
                                                        <option value="disetujui" <?= $j->status_verifikasi === 'disetujui' ? 'selected' : '' ?>>
                                                            Setujui Laporan Jurnal (Terkunci untuk Siswa)
                                                        </option>
                                                        <option value="revisi" <?= $j->status_verifikasi === 'revisi' ? 'selected' : '' ?>>
                                                            Minta Revisi / Perbaikan Siswa (Buka Kunci)
                                                        </option>
                                                        <option value="menunggu" <?= $j->status_verifikasi === 'menunggu' ? 'selected' : '' ?>>
                                                            Buka Kunci (Kembalikan ke Menunggu Verifikasi)
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="form-group mb-2">
                                                    <label class="font-weight-bold text-gray-700 text-xs text-uppercase">Catatan / Masukan Evaluasi Pembimbing</label>
                                                    <textarea name="catatan_pembimbing" class="form-control" rows="3" placeholder="Tuliskan evaluasi, saran perbaikan, atau apresiasi atas pekerjaan yang dilakukan siswa..."><?= esc($j->catatan_pembimbing ?? '') ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary rounded-lg" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary font-weight-bold rounded-lg shadow-sm">
                                                    <i class="fas fa-save mr-1"></i> Simpan Verifikasi
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
