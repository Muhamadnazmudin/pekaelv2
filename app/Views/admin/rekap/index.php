<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Rekapitulasi PKL & Pembimbing (Dapodik Ready)</h1>
        <p class="text-muted text-sm mb-0">Format data final siap input / copy-paste ke dalam modul Pembelajaran & PKL pada aplikasi Dapodik.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= site_url('rekappkl/export_excel') ?>" class="btn btn-success shadow-sm px-3 py-2 font-weight-bold">
            <i class="fas fa-file-excel mr-1"></i> Ekspor ke File Excel (.xlsx)
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-table mr-2"></i> Master Rekapitulasi PKL Tahun Pelajaran <?= esc($tahun->tahun ?? '-') ?></h6>
        <span class="badge badge-light border text-xs"><?= count($rekap) ?> Siswa Terdaftar</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th style="width: 140px;">NISN Siswa</th>
                        <th>Nama Lengkap Siswa</th>
                        <th>Rombel / Kelas</th>
                        <th>Guru Pembimbing</th>
                        <th>NIP Pembimbing</th>
                        <th>Mitra DUDI</th>
                        <th>Nomor MoU Dapodik</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rekap)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-2 text-gray-300"></i>
                                <p class="mb-0">Belum ada data distribusi PKL yang selesai. Silakan lakukan alokasi pembagian jam dan mapping siswa asuh terlebih dahulu.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($rekap as $r): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <span class="text-monospace font-weight-bold text-primary"><?= esc($r->nisn) ?></span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-gray-900"><?= esc($r->nama_siswa) ?></div>
                                </td>
                                <td>
                                    <span class="badge badge-info px-2 py-1"><?= esc($r->nama_kelas) ?></span>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-gray-800"><?= esc($r->nama_pembimbing) ?></span>
                                </td>
                                <td>
                                    <span class="text-monospace text-secondary"><?= esc($r->nip_pembimbing ?: '-') ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($r->nama_dudi)): ?>
                                        <span class="font-weight-bold text-gray-800"><i class="fas fa-building text-muted mr-1"></i> <?= esc($r->nama_dudi) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
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
