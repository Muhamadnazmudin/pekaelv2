<?= $this->extend('portal_guru/layout') ?>

<?= $this->section('content') ?>

<!-- Header Kaprog -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <div class="d-inline-flex align-items-center gap-2 mb-1">
            <span class="badge badge-success px-2.5 py-1 text-xs font-weight-bold" style="border-radius: 6px;">
                <i class="fas fa-crown mr-1"></i> Hak Akses: Kepala Program Keahlian (Kaprog)
            </span>
        </div>
        <h4 class="font-weight-800 text-gray-900 mb-1" style="letter-spacing: -0.02em;">
            <i class="fas fa-chart-line text-success mr-2"></i> Monitoring Praktik Kerja Lapangan Jurusan
        </h4>
        <p class="text-muted text-xs mb-0">
            Memantau seluruh penempatan DUDI, guru pembimbing, dan aktivitas presensi siswa pada kompetensi keahlian: 
            <strong>
                <?php 
                    $descParts = [];
                    foreach ($jurusans as $j) {
                        if (($j->total_kelas ?? 0) > 0) {
                            $descParts[] = esc($j->nama_jurusan) . " (" . $j->total_kelas . " Rombel)";
                        } else {
                            $descParts[] = esc($j->nama_jurusan) . " (0 Rombel)";
                        }
                    }
                    echo implode(', ', $descParts);
                ?>
            </strong>
        </p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= site_url('guru/dashboard') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 font-weight-bold">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm rounded-xl h-100 p-3" style="border-left: 4px solid #10b981 !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Total Siswa Jurusan</div>
                    <div class="h4 mb-0 font-weight-800 text-gray-900"><?= number_format($totalSiswaJurusan) ?></div>
                    <div class="text-xs text-muted mt-1"><?= count($kelas) ?> Rombongan Belajar Terdaftar</div>
                </div>
                <div class="w-12 h-12 rounded-circle bg-emerald-50 text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #ecfdf5;">
                    <i class="fas fa-users-cog fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm rounded-xl h-100 p-3" style="border-left: 4px solid #3b82f6 !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Sudah Ditempatkan</div>
                    <div class="h4 mb-0 font-weight-800 text-primary"><?= number_format($totalSudahDitempatkan) ?></div>
                    <div class="text-xs text-muted mt-1">
                        <?= $totalSiswaJurusan > 0 ? round(($totalSudahDitempatkan / $totalSiswaJurusan) * 100, 1) : 0 ?>% dari total siswa
                    </div>
                </div>
                <div class="w-12 h-12 rounded-circle bg-blue-50 text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #eff6ff;">
                    <i class="fas fa-building fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm rounded-xl h-100 p-3" style="border-left: 4px solid #f59e0b !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Belum Terpetakan DUDI</div>
                    <?php $belumDitempatkan = max(0, $totalSiswaJurusan - $totalSudahDitempatkan); ?>
                    <div class="h4 mb-0 font-weight-800 text-warning"><?= number_format($belumDitempatkan) ?></div>
                    <div class="text-xs text-muted mt-1">Perlu pemetaan DUDI</div>
                </div>
                <div class="w-12 h-12 rounded-circle bg-amber-50 text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #fffbeb;">
                    <i class="fas fa-user-clock fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm rounded-xl h-100 p-3" style="border-left: 4px solid #6366f1 !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Hadir PKL Hari Ini</div>
                    <div class="h4 mb-0 font-weight-800 text-indigo" style="color: #4f46e5;"><?= number_format($totalHadirHariIni) ?></div>
                    <div class="text-xs text-muted mt-1">Presensi GPS terverifikasi</div>
                </div>
                <div class="w-12 h-12 rounded-circle bg-indigo-50 text-indigo d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #eef2ff; color: #4f46e5;">
                    <i class="fas fa-map-marker-alt fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search Controls -->
<div class="card border-0 shadow-sm rounded-xl mb-4">
    <div class="card-body p-3">
        <form method="get" action="<?= site_url('guru/kaprog') ?>" class="row align-items-center">
            <div class="col-md-3 mb-2 mb-md-0">
                <label class="text-xs font-weight-bold text-muted mb-1 d-block"><i class="fas fa-layer-group mr-1"></i> Program Keahlian:</label>
                <select name="jurusan_id" class="form-control form-control-sm rounded-pill" onchange="this.form.submit()">
                    <option value="">-- Semua Jurusan Binaan --</option>
                    <?php foreach ($jurusans as $j): ?>
                        <option value="<?= $j->id ?>" <?= ($jurusanPilih == $j->id) ? 'selected' : '' ?>>
                            <?= esc($j->nama_jurusan) ?> (<?= $j->total_kelas ?? 0 ?> Rombel)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <label class="text-xs font-weight-bold text-muted mb-1 d-block"><i class="fas fa-filter mr-1"></i> Rombel / Kelas:</label>
                <select name="kelas_id" class="form-control form-control-sm rounded-pill" onchange="this.form.submit()">
                    <option value="">-- Semua Rombel (<?= count($kelas) ?> Kelas) --</option>
                    <?php foreach ($kelas as $k): ?>
                        <option value="<?= $k->id ?>" <?= ($kelasPilih == $k->id) ? 'selected' : '' ?>>
                            <?= esc($k->nama_kelas) ?> &bull; <?= esc($k->nama_jurusan) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 mb-2 mb-md-0">
                <label class="text-xs font-weight-bold text-muted mb-1 d-block"><i class="fas fa-search mr-1"></i> Cari Cepat Siswa / Mitra:</label>
                <input type="text" id="liveSearchInput" class="form-control form-control-sm rounded-pill" placeholder="Ketik nama siswa, NISN, atau DUDI...">
            </div>
            <div class="col-md-2 text-md-right mt-2 mt-md-0 pt-md-3">
                <span class="badge badge-light border text-dark font-weight-bold py-2 px-3">
                    Total: <span id="shownCount"><?= count($allSiswa) ?></span> Siswa
                </span>
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card border-0 shadow-sm rounded-xl overflow-hidden">
    <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
        <h6 class="font-weight-bold text-gray-900 mb-0">
            <i class="fas fa-list text-success mr-2"></i> Daftar Lengkap Siswa PKL Jurusan
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableKaprog">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Mitra DUDI &amp; PIC</th>
                        <th>Guru Pembimbing</th>
                        <th style="width: 140px;" class="text-center">Status Absen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($allSiswa)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 text-gray-300 d-block"></i>
                                Tidak ada data siswa ditemukan untuk kriteria filter ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($allSiswa as $s): ?>
                            <tr class="siswa-row">
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="font-weight-bold text-gray-900 text-sm searchable-name"><?= esc($s->nama) ?></div>
                                    <div class="text-xs text-muted searchable-nisn">NISN: <?= esc($s->nisn) ?></div>
                                </td>
                                <td>
                                    <span class="badge badge-light border text-dark font-weight-bold"><?= esc($s->nama_kelas) ?></span>
                                    <div class="text-xs text-muted mt-0.5"><?= esc($s->nama_jurusan) ?></div>
                                </td>
                                <td>
                                    <?php if (!empty($s->nama_dudi)): ?>
                                        <div class="font-weight-bold text-primary text-sm searchable-dudi">
                                            <i class="fas fa-building mr-1"></i> <?= esc($s->nama_dudi) ?>
                                        </div>
                                        <div class="text-xs text-muted">
                                            PIC: <?= esc($s->nama_pic ?: '-') ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge badge-warning text-dark px-2 py-0.5 text-xs font-weight-bold">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Belum DUDI
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($s->nama_pembimbing)): ?>
                                        <div class="text-sm font-weight-bold text-gray-900">
                                            <i class="fas fa-chalkboard-teacher text-success mr-1"></i> <?= esc($s->nama_pembimbing) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-muted font-italic">Belum dialokasikan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($s->status_absen_hari_ini === 'hadir'): ?>
                                        <span class="badge badge-success px-2.5 py-1 text-xs font-weight-bold">
                                            <i class="fas fa-check-circle mr-1"></i> Hadir
                                        </span>
                                    <?php elseif ($s->status_absen_hari_ini === 'izin'): ?>
                                        <span class="badge badge-info px-2.5 py-1 text-xs font-weight-bold">
                                            <i class="fas fa-info-circle mr-1"></i> Izin
                                        </span>
                                    <?php elseif ($s->status_absen_hari_ini === 'sakit'): ?>
                                        <span class="badge badge-warning text-dark px-2.5 py-1 text-xs font-weight-bold">
                                            <i class="fas fa-first-aid mr-1"></i> Sakit
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-light border text-muted px-2.5 py-1 text-xs">
                                            Belum Absen
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearchInput');
    const rows = document.querySelectorAll('.siswa-row');
    const shownCountEl = document.getElementById('shownCount');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let count = 0;

            rows.forEach(function(row) {
                const text = row.innerText.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (shownCountEl) {
                shownCountEl.textContent = count;
            }
        });
    }
});
</script>

<?= $this->endSection() ?>
