<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Laporan Pembimbing PKL (Metode Koefisien)</h1>
        <p class="text-muted text-sm mb-0">Rincian beban jam mengajar dan daftar siswa asuh per guru pembimbing untuk tahun pelajaran <strong><?= esc($tahun->tahun ?? '-') ?></strong>.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= site_url('laporanpembimbing/cetak') ?>" target="_blank" class="btn btn-outline-primary shadow-sm mr-2">
            <i class="fas fa-print mr-1"></i> Cetak Laporan Resmi
        </a>
        <a href="<?= site_url('rekappkl/export_excel') ?>" class="btn btn-success shadow-sm">
            <i class="fas fa-file-excel mr-1"></i> Ekspor Excel Dapodik
        </a>
    </div>
</div>

<?php if (empty($laporan)): ?>
    <div class="card shadow p-5 text-center text-muted">
        <i class="fas fa-clipboard-list fa-3x mb-3 text-gray-400"></i>
        <h5 class="font-weight-bold text-gray-700">Belum Ada Data Laporan Pembimbing</h5>
        <p class="mb-0">Pastikan Anda telah mengisi pembagian jam dan menyelesaikan distribusi siswa pada metode koefisien.</p>
    </div>
<?php else: ?>
    <?php foreach ($laporan as $lp): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h5 class="m-0 font-weight-bold text-primary"><?= esc($lp['guru']->nama_guru) ?></h5>
                    <span class="text-xs text-muted">NIP: <?= esc($lp['guru']->nip ?: '-') ?></span>
                </div>
                <div class="mt-2 mt-md-0 d-flex gap-2">
                    <span class="badge badge-info px-3 py-2 font-weight-bold mr-2">
                        Total Beban: <?= $lp['total_jam'] ?> JP
                    </span>
                    <span class="badge badge-success px-3 py-2 font-weight-bold">
                        Total Asuhan: <?= $lp['total_siswa'] ?> Siswa
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th style="width: 140px;">Kelas / Rombel</th>
                                <th style="width: 140px;">NISN</th>
                                <th>Nama Lengkap Siswa</th>
                                <th>Mitra DUDI Tempat PKL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($lp['kelas'] as $k): ?>
                                <?php if (empty($k['daftar_siswa'])): ?>
                                    <tr>
                                        <td class="text-center text-muted"><?= $no++ ?></td>
                                        <td><span class="badge badge-light border"><?= esc($k['nama_kelas']) ?></span></td>
                                        <td colspan="3" class="text-muted font-italic">Belum ada siswa yang dipilih di rombel ini (Beban: <?= $k['jam'] ?> JP)</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($k['daftar_siswa'] as $s): ?>
                                        <tr>
                                            <td class="text-center text-muted"><?= $no++ ?></td>
                                            <td><span class="badge badge-light border"><?= esc($k['nama_kelas']) ?></span></td>
                                            <td class="text-monospace font-weight-bold text-primary"><?= esc($s->nisn) ?></td>
                                            <td class="font-weight-bold text-gray-900"><?= esc($s->nama) ?></td>
                                            <td>
                                                <?php if (!empty($s->nama_dudi)): ?>
                                                    <span class="font-weight-bold text-gray-800"><i class="fas fa-building text-muted mr-1"></i> <?= esc($s->nama_dudi) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
