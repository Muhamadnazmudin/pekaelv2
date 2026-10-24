<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Rekap Data PKL (Metode Proporsional Kelas)</h1>
        <p class="text-muted text-sm mb-0">Tabel keseluruhan siswa peserta PKL, mitra DUDI, dan guru pembimbing tahun pelajaran <strong><?= esc($tahun->tahun ?? '-') ?></strong>.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= site_url('laporan_propor/pembimbing') ?>" class="btn btn-outline-secondary shadow-sm mr-2">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Laporan Guru
        </a>
        <a href="<?= site_url('laporan_propor/cetak') ?>" target="_blank" class="btn btn-outline-primary shadow-sm">
            <i class="fas fa-print mr-1"></i> Cetak Dokumen
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-table mr-2"></i> Rekapitulasi Alokasi PKL</h6>
        <span class="badge badge-light border text-xs"><?= count($rekap) ?> Siswa Terdata</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th style="width: 140px;">NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas / Rombel</th>
                        <th>Guru Pembimbing</th>
                        <th>Mitra DUDI</th>
                        <th>No MoU / PKS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rekap)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data rekapitulasi proporsional.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($rekap as $r): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <span class="text-monospace font-weight-bold text-primary"><?= esc($r->nisn) ?></span>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-gray-900"><?= esc($r->nama_siswa) ?></span>
                                </td>
                                <td>
                                    <span class="badge badge-info px-2 py-1"><?= esc($r->nama_kelas) ?></span>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-gray-800"><?= esc($r->nama_pembimbing) ?></span>
                                </td>
                                <td>
                                    <?= esc($r->nama_dudi ?: '-') ?>
                                </td>
                                <td>
                                    <span class="text-xs text-muted"><?= esc($r->nomor_mou ?: '-') ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
