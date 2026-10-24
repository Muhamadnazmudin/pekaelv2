<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Backup Database PEKAEL</h1>
        <p class="text-muted text-sm mb-0">Cadangkan seluruh struktur skema dan data tabel PEKAEL ke dalam format file SQL.</p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="<?= site_url('backup/download') ?>" class="btn btn-success shadow-sm px-3 py-2 font-weight-bold">
            <i class="fas fa-download mr-1"></i> Unduh File Backup (.sql)
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-database mr-2"></i> Daftar Tabel yang Dicadangkan</h6>
                <span class="badge badge-light border text-xs"><?= count($tables) ?> Tabel</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Nama Tabel</th>
                                <th class="text-center" style="width: 140px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($tables as $t): ?>
                                <tr>
                                    <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                    <td>
                                        <span class="font-weight-bold text-gray-900 font-monospace"><?= esc($t) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Termasuk</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4 border-left-warning">
            <div class="card-body p-4">
                <h6 class="font-weight-bold text-gray-900 mb-2"><i class="fas fa-shield-alt text-warning mr-1"></i> Keamanan Data</h6>
                <p class="text-xs text-muted mb-3">
                    Lakukan backup database secara rutin terutama setelah selesai melakukan penarikan data dari Dapodik dan alokasi pembimbing siswa.
                </p>
                <div class="p-3 bg-light rounded border text-xs text-secondary">
                    File SQL hasil backup kompatibel dengan MySQL 8.x dan MariaDB 10.x serta dapat diimpor langsung melalui phpMyAdmin Laragon atau terminal SQL.
                </div>
            </div>
        </div>
    </div>
</div>
