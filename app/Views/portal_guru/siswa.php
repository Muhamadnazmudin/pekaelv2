<?= $this->extend('portal_guru/layout') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-4">
    <div>
        <h4 class="font-weight-800 text-gray-900 mb-1" style="letter-spacing: -0.02em;">
            <i class="fas fa-users text-primary mr-2"></i> Siswa Bimbingan PKL Saya
        </h4>
        <p class="text-muted text-xs mb-0">Kelola dan pantau seluruh siswa yang dialokasikan di bawah bimbingan Anda pada periode aktif.</p>
    </div>
    <div class="mt-2 mt-sm-0">
        <span class="badge badge-primary px-3 py-2 font-weight-bold" style="font-size: 0.85rem;">
            Total: <?= count($siswaBimbingan) ?> Siswa
        </span>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-xl overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Identitas Siswa</th>
                        <th>Kelas &amp; Jurusan</th>
                        <th>Mitra DUDI Tempat PKL</th>
                        <th>Pembimbing Industri (PIC)</th>
                        <th style="width: 140px;" class="text-center">Status Absen Hari Ini</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($siswaBimbingan)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada siswa yang dialokasikan di bawah bimbingan Anda.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($siswaBimbingan as $s): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="font-weight-bold text-gray-900 text-sm"><?= esc($s->nama) ?></div>
                                    <div class="text-xs text-muted">NISN: <?= esc($s->nisn) ?></div>
                                </td>
                                <td>
                                    <span class="badge badge-light border text-dark font-weight-bold"><?= esc($s->nama_kelas) ?></span>
                                    <div class="text-xs text-muted mt-0.5"><?= esc($s->nama_jurusan) ?></div>
                                </td>
                                <td>
                                    <?php if (!empty($s->nama_dudi)): ?>
                                        <div class="font-weight-bold text-primary text-sm">
                                            <i class="fas fa-building mr-1"></i> <?= esc($s->nama_dudi) ?>
                                        </div>
                                        <div class="text-xs text-muted"><?= esc($s->alamat_dudi ?: 'Alamat belum diset.') ?></div>
                                    <?php else: ?>
                                        <span class="badge badge-warning text-dark px-2 py-0.5 text-xs">Belum Ada DUDI</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-sm font-weight-bold text-gray-800"><?= esc($s->nama_pic ?: '-') ?></div>
                                    <?php if (!empty($s->no_hp_pic)): ?>
                                        <div class="text-xs text-muted"><i class="fab fa-whatsapp text-success mr-1"></i><?= esc($s->no_hp_pic) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($s->status_absen_hari_ini === 'hadir'): ?>
                                        <span class="badge badge-soft-success px-2.5 py-1 text-xs font-weight-bold">
                                            <i class="fas fa-check mr-1"></i> Masuk <?= esc($s->jam_masuk) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-light border text-danger px-2.5 py-1 text-xs font-weight-bold">
                                            <i class="fas fa-clock mr-1"></i> Belum Masuk
                                        </span>
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

<?= $this->endSection() ?>
