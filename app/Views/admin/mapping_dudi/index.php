<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Mapping Penempatan DUDI</h1>
        <p class="text-muted text-sm mb-0">Petakan siswa peserta PKL ke mitra DUDI secara presisi dan kelola riwayat mutasi / perpindahan mitra.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-primary shadow-sm" id="btnBukaModalMapping">
            <i class="fas fa-user-plus mr-1"></i> Tempatkan Siswa ke DUDI
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

<!-- Stat Widgets -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-widget h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-xs font-weight-bold text-uppercase text-primary" style="letter-spacing: 0.05em;">Total Siswa PKL</span>
                    <div class="stat-icon bg-primary-light text-primary">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div class="stat-number text-dark mb-1"><?= $stats['total_siswa'] ?></div>
                <div class="text-xs text-muted">Peserta tingkat XII sasaran PKL</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-widget h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-xs font-weight-bold text-uppercase text-success" style="letter-spacing: 0.05em;">Sudah Ditempatkan</span>
                    <div class="stat-icon bg-success-light text-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-number text-dark mb-1"><?= $stats['total_ditempatkan'] ?></div>
                <div class="text-xs text-muted">
                    <?php $pct = $stats['total_siswa'] > 0 ? round(($stats['total_ditempatkan'] / $stats['total_siswa']) * 100) : 0; ?>
                    <span class="text-success font-weight-bold"><?= $pct ?>%</span> siswa memiliki mitra DUDI
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-widget h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-xs font-weight-bold text-uppercase text-warning" style="letter-spacing: 0.05em;">Belum Ditempatkan</span>
                    <div class="stat-icon bg-warning-light text-warning">
                        <i class="fas fa-user-clock"></i>
                    </div>
                </div>
                <div class="stat-number text-dark mb-1"><?= $stats['total_belum'] ?></div>
                <div class="text-xs text-muted">Menunggu penempatan ke DUDI</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-widget h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-xs font-weight-bold text-uppercase text-info" style="letter-spacing: 0.05em;">Mitra DUDI Aktif</span>
                    <div class="stat-icon bg-info-light text-info">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
                <div class="stat-number text-dark mb-1"><?= $stats['total_dudi'] ?></div>
                <div class="text-xs text-muted">Mitra kerjasama siap menerima siswa</div>
            </div>
        </div>
    </div>
</div>

<!-- Main Section: Placed Table & Unplaced Sidebar -->
<div class="row">
    <!-- Unplaced Students Side Panel -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-user-clock mr-1"></i> Siswa Belum Ditempatkan
                </h6>
                <span class="badge badge-warning font-weight-bold"><?= count($siswa_belum) ?> Siswa</span>
            </div>
            <div class="p-3 bg-light border-bottom">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="filterBelumInput" class="form-control" placeholder="Cari nama siswa / rombel...">
                </div>
            </div>
            <div class="card-body p-0" style="max-height: 520px; overflow-y: auto;" id="listSiswaBelumContainer">
                <?php if (empty($siswa_belum)): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-check-circle text-success fa-3x mb-2"></i>
                        <h6 class="font-weight-bold text-gray-800">Semua Siswa Terpetakan!</h6>
                        <p class="mb-0 text-xs">Seluruh siswa sasaran PKL telah berhasil ditempatkan ke mitra DUDI.</p>
                    </div>
                <?php else: ?>
                    <ul class="list-group list-group-flush" id="ulSiswaBelum">
                        <?php foreach ($siswa_belum as $sb): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3 item-siswa-belum" data-search="<?= strtolower(esc($sb->nama . ' ' . $sb->nama_kelas . ' ' . $sb->nisn)) ?>">
                                <div>
                                    <div class="font-weight-bold text-gray-900 text-sm"><?= esc($sb->nama) ?></div>
                                    <div class="text-xs text-muted">
                                        <span class="badge badge-light border"><?= esc($sb->nama_kelas) ?></span> &bull; NISN: <?= esc($sb->nisn ?: '-') ?>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-petakan-satuan" data-siswa-id="<?= $sb->id ?>" data-nama-siswa="<?= esc($sb->nama) ?>" title="Tempatkan siswa ini">
                                    <i class="fas fa-plus"></i> Petakan
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Placed Students DataTable -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-map-marked-alt mr-2"></i> Daftar Penempatan Siswa di Mitra DUDI
                </h6>
                <span class="badge badge-light border text-xs"><?= count($mapping) ?> Siswa Terpetakan</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table class="table table-hover align-middle mb-0 dataTable">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 40px;" class="text-center">No</th>
                                <th>Nama Siswa & Rombel</th>
                                <th>Mitra DUDI & Tahap PKL</th>
                                <th>Pembimbing</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($mapping)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data penempatan. Silakan klik tombol "Tempatkan Siswa ke DUDI".</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($mapping as $m): ?>
                                    <?php 
                                        $tahap = !empty($m->tahap_pkl) ? $m->tahap_pkl : 'PKL 1';
                                        $jmlPindah = $history_map[$m->siswa_id] ?? 0;
                                    ?>
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                        <td>
                                            <div class="font-weight-bold text-gray-900"><?= esc($m->nama_siswa) ?></div>
                                            <div class="text-xs text-muted">
                                                <span class="badge badge-light border"><?= esc($m->nama_kelas) ?></span> &bull; NISN: <?= esc($m->nisn ?: '-') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="font-weight-bold text-primary mr-2">
                                                    <i class="fas fa-building text-muted mr-1"></i><?= esc($m->nama_dudi) ?>
                                                </span>
                                                <span class="badge <?= ($tahap === 'PKL 1') ? 'badge-primary' : 'badge-info' ?> px-2 py-0" style="font-size: 0.75rem;">
                                                    <?= esc($tahap) ?>
                                                </span>
                                            </div>
                                            <div class="text-xs text-secondary mb-1">
                                                <?= esc($m->alamat_dudi ?? $m->alamat ?? '-') ?>
                                                <?php if (!empty($m->kecamatan)): ?>
                                                    (Kec. <?= esc($m->kecamatan) ?>)
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($jmlPindah > 0): ?>
                                                <div>
                                                    <a href="javascript:void(0)" class="badge badge-warning text-dark font-weight-bold btn-view-riwayat" data-siswa-id="<?= $m->siswa_id ?>" title="Lihat Riwayat Pindah DUDI">
                                                        <i class="fas fa-history mr-1"></i> <?= $jmlPindah ?>x Pindah DUDI
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="text-sm font-weight-bold text-gray-800"><?= esc($m->nama_guru ?: '-') ?></div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-info shadow-sm btn-pindah-dudi" 
                                                    data-mapping-id="<?= $m->id ?>" 
                                                    data-siswa-id="<?= $m->siswa_id ?>"
                                                    data-nama-siswa="<?= esc($m->nama_siswa) ?>"
                                                    data-nama-kelas="<?= esc($m->nama_kelas) ?>"
                                                    data-dudi-id="<?= $m->dudi_id ?>"
                                                    data-nama-dudi="<?= esc($m->nama_dudi) ?>"
                                                    data-tahap="<?= esc($tahap) ?>"
                                                    title="Pindah DUDI (Mutasi)">
                                                    <i class="fas fa-exchange-alt"></i> Pindah
                                                </button>
                                                <a href="<?= site_url('mappingdudi/hapus/' . $m->id) ?>" class="btn btn-outline-danger shadow-sm btn-hapus" title="Reset / Batalkan Penempatan (Kembali ke belum ditempatkan)">
                                                    <i class="fas fa-undo"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL TEMPATKAN SISWA KE DUDI (PENCARIAN DUDI & SISWA MUDAH)   -->
<!-- ============================================================== -->
<div class="modal fade" id="modalMappingDudi" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-plus fa-lg mr-2"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0">Tempatkan Siswa ke Mitra DUDI</h5>
                        <small class="text-white-50">Cari DUDI sasaran, lalu pilih siswa yang belum terpetakan</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form action="<?= site_url('mappingdudi/simpan') ?>" method="POST" id="formMappingDudi">
                <?= csrf_field() ?>
                <input type="hidden" name="dudi_id" id="selectedDudiId" value="" required>

                <div class="modal-body p-4">
                    <div class="row">
                        <!-- KOLOM KIRI: CARI & PILIH DUDI -->
                        <div class="col-lg-5 mb-4 mb-lg-0 border-right">
                            <h6 class="font-weight-bold text-gray-900 mb-2">
                                <span class="badge badge-primary rounded-circle mr-1">1</span> Cari & Pilih Mitra DUDI:
                            </h6>

                            <!-- Search box DUDI -->
                            <div class="input-group mb-3 shadow-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fas fa-search text-primary"></i></span>
                                </div>
                                <input type="text" id="searchDudiInput" class="form-control font-weight-bold" placeholder="Ketik nama DUDI... (misal: Indah Grosir)" autofocus>
                            </div>

                            <!-- Selected DUDI Alert -->
                            <div id="selectedDudiBanner" class="alert alert-success d-none mb-3 p-3 border-0 shadow-sm">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-xs text-uppercase font-weight-bold text-success mb-1">DUDI Terpilih:</div>
                                        <h6 class="font-weight-bold text-gray-900 mb-0" id="selectedDudiName">-</h6>
                                        <div class="text-xs text-muted" id="selectedDudiDetails">-</div>
                                    </div>
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Terpilih</span>
                                </div>
                            </div>

                            <!-- List DUDI Container -->
                            <div class="border rounded bg-light p-2" style="max-height: 380px; overflow-y: auto;" id="dudiCardsContainer">
                                <?php if (empty($dudi)): ?>
                                    <div class="p-3 text-center text-muted">Belum ada master DUDI aktif.</div>
                                <?php else: ?>
                                    <?php foreach ($dudi as $du): ?>
                                        <div class="card mb-2 dudi-select-card cursor-pointer border-0 shadow-sm" data-dudi-id="<?= $du->id ?>" data-dudi-name="<?= esc($du->nama_dudi) ?>" data-dudi-alamat="<?= esc($du->alamat ?: '') ?>" data-dudi-bidang="<?= esc($du->bidang_usaha ?: 'Industri') ?>" data-search="<?= strtolower(esc($du->nama_dudi . ' ' . $du->alamat . ' ' . $du->bidang_usaha . ' ' . $du->kecamatan)) ?>">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="font-weight-bold text-gray-900 mb-1 dudi-title"><?= esc($du->nama_dudi) ?></h6>
                                                        <div class="text-xs text-muted mb-1">
                                                            <i class="fas fa-tag mr-1 text-secondary"></i> <?= esc($du->bidang_usaha ?: 'Umum / Industri') ?>
                                                        </div>
                                                        <div class="text-xs text-secondary">
                                                            <i class="fas fa-map-marker-alt mr-1 text-danger"></i> <?= esc($du->alamat ?: 'Alamat belum diatur') ?>
                                                        </div>
                                                    </div>
                                                    <span class="badge badge-light border text-xs"><?= (int)($du->total_siswa_dudi ?? 0) ?> Siswa PKL</span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <!-- Guru Pembimbing Opsional -->
                            <div class="form-group mt-3 mb-0">
                                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Guru Pembimbing Lapangan (Opsional):</label>
                                <select name="guru_id" class="form-control form-control-sm">
                                    <option value="0">-- Belum Ditentukan --</option>
                                    <?php foreach ($guru as $g): ?>
                                        <option value="<?= $g->id ?>"><?= esc($g->nama_guru) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- KOLOM KANAN: PILIH SISWA -->
                        <div class="col-lg-7">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="font-weight-bold text-gray-900 mb-0">
                                    <span class="badge badge-primary rounded-circle mr-1">2</span> Pilih Siswa untuk Ditempatkan:
                                </h6>
                                <span class="badge badge-primary px-3 py-1 font-weight-bold" id="badgeTotalSelected">0 Siswa Dipilih</span>
                            </div>

                            <!-- Filter & Search Controls -->
                            <div class="row mb-2">
                                <div class="col-sm-5 mb-2 mb-sm-0">
                                    <select id="filterKelasModal" class="form-control form-control-sm font-weight-bold text-primary">
                                        <option value="">-- Semua Kelas / Rombel --</option>
                                        <?php foreach ($kelas_list as $k): ?>
                                            <option value="<?= esc($k->nama_kelas) ?>"><?= esc($k->nama_kelas) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-7">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                        </div>
                                        <input type="text" id="searchSiswaModal" class="form-control" placeholder="Cari nama siswa / NISN...">
                                    </div>
                                </div>
                            </div>

                            <!-- Select All Toolbar -->
                            <div class="d-flex justify-content-between align-items-center bg-light px-3 py-2 rounded mb-2 border">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="chkSelectAllFiltered">
                                    <label class="custom-control-label font-weight-bold text-xs" for="chkSelectAllFiltered">Pilih Semua yang Tampil</label>
                                </div>
                                <span class="text-xs text-muted" id="lblTampilCount">Menampilkan <?= count($siswa_belum) ?> siswa</span>
                            </div>

                            <!-- Student Checkbox Container -->
                            <div class="border rounded p-2 bg-light" style="max-height: 380px; overflow-y: auto;" id="siswaCheckboxContainer">
                                <?php if (empty($siswa_belum)): ?>
                                    <div class="p-4 text-center text-muted">
                                        <i class="fas fa-check-circle text-success fa-3x mb-2"></i>
                                        <h6 class="font-weight-bold">Semua Siswa Sudah Ditempatkan!</h6>
                                        <p class="text-xs mb-0">Jika ingin memindahkan siswa yang sudah punya DUDI, gunakan tombol <strong>Pindah</strong> pada tabel penempatan.</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($siswa_belum as $sb): ?>
                                        <div class="card mb-2 student-chk-item border-0 shadow-sm" data-nama="<?= strtolower(esc($sb->nama)) ?>" data-kelas="<?= esc($sb->nama_kelas) ?>" data-nisn="<?= esc($sb->nisn) ?>" data-search="<?= strtolower(esc($sb->nama . ' ' . $sb->nama_kelas . ' ' . $sb->nisn)) ?>">
                                            <div class="card-body p-2 px-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="siswa_id[]" value="<?= $sb->id ?>" class="custom-control-input chk-siswa-target" id="chkS_<?= $sb->id ?>">
                                                    <label class="custom-control-label w-100 cursor-pointer" for="chkS_<?= $sb->id ?>">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <span class="font-weight-bold text-gray-900"><?= esc($sb->nama) ?></span>
                                                                <span class="text-xs text-muted ml-2">&bull; NISN: <?= esc($sb->nisn ?: '-') ?></span>
                                                            </div>
                                                            <span class="badge badge-info px-2 py-1"><?= esc($sb->nama_kelas) ?></span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-sm font-weight-bold text-gray-800" id="footerPlacementSummary">
                            Silakan pilih DUDI dan minimal 1 siswa.
                        </span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary font-weight-bold shadow-sm" id="btnSubmitMapping" disabled>
                            <i class="fas fa-save mr-1"></i> Simpan Penempatan Siswa
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL PINDAH DUDI (MUTASI PKL DENGAN RIWAYAT LENGKAP)          -->
<!-- ============================================================== -->
<div class="modal fade" id="modalPindahDudi" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exchange-alt fa-lg mr-2"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0">Mutasi / Pindah Penempatan DUDI</h5>
                        <small class="text-white-50">Siswa akan dipindahkan ke DUDI baru & riwayat perpindahan dicatat</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form action="<?= site_url('mappingdudi/pindah') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="mapping_id" id="pindahMappingId" value="" required>

                <div class="modal-body p-4">
                    <!-- Student Info Box -->
                    <div class="card bg-light border-0 mb-3 p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-xs text-muted text-uppercase font-weight-bold">Siswa Peserta PKL:</div>
                                <h6 class="font-weight-bold text-gray-900 mb-0" id="pindahNamaSiswa">-</h6>
                                <div class="text-xs text-primary font-weight-bold" id="pindahNamaKelas">-</div>
                            </div>
                            <span class="badge badge-primary px-2 py-1 font-weight-bold" id="pindahTahapBadge">PKL 1</span>
                        </div>
                        <hr class="my-2">
                        <div class="text-xs text-muted">
                            Mitra DUDI Saat Ini: <strong class="text-danger" id="pindahDudiAsal">-</strong>
                        </div>
                    </div>

                    <!-- Pilih DUDI Baru -->
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-800">Pilih Mitra DUDI Baru (Tujuan Mutasi):</label>
                        <select name="dudi_tujuan_id" id="pindahDudiTujuanSelect" class="form-control font-weight-bold" required>
                            <option value="">-- Pilih Mitra DUDI Baru --</option>
                            <?php foreach ($dudi as $du): ?>
                                <option value="<?= $du->id ?>">
                                    <?= esc($du->nama_dudi) ?> (<?= esc($du->bidang_usaha ?: 'Industri') ?> - <?= esc($du->alamat ?: 'Alamat belum diatur') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Siswa akan otomatis dialihkan ke DUDI baru dengan penomoran tahap berikutnya (misal: PKL 2).</small>
                    </div>

                    <!-- Guru Pembimbing Opsional -->
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-800">Guru Pembimbing Lapangan (Opsional):</label>
                        <select name="guru_id" class="form-control">
                            <option value="0">-- Pertahankan Pembimbing Saat Ini --</option>
                            <?php foreach ($guru as $g): ?>
                                <option value="<?= $g->id ?>"><?= esc($g->nama_guru) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Alasan Pindah -->
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-gray-800">Alasan Pindah DUDI:</label>
                        <textarea name="alasan_pindah" class="form-control" rows="3" placeholder="Contoh: Penutupan cabang industri, rotasi kompetensi kejuruan, kendala teknis lapangan..." required></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info font-weight-bold shadow-sm">
                        <i class="fas fa-check mr-1"></i> Simpan & Catat Riwayat Pindah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL LIHAT RIWAYAT PERPINDAHAN DUDI SISWA                     -->
<!-- ============================================================== -->
<div class="modal fade" id="modalRiwayatPkl" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark">
                <div class="d-flex align-items-center">
                    <i class="fas fa-history fa-lg mr-2"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0">Riwayat Penempatan & Pindah DUDI</h5>
                        <small class="text-dark-50" id="riwayatModalSub">Log mutasi PKL siswa</small>
                    </div>
                </div>
                <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div id="riwayatContent">
                    <div class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat riwayat...</div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
.cursor-pointer { cursor: pointer; }
.dudi-select-card {
    transition: all 0.2s ease-in-out;
    border: 2px solid transparent !important;
}
.dudi-select-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
}
.dudi-select-card.selected-dudi {
    border: 2px solid #4f46e5 !important;
    background-color: #eef2ff !important;
}
.student-chk-item {
    transition: background-color 0.15s ease;
}
.student-chk-item:hover {
    background-color: #f1f5f9;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentSelectedDudi = null;

    // Buka Modal Mapping Utama
    $('#btnBukaModalMapping').on('click', function() {
        $('#modalMappingDudi').modal('show');
    });

    // Petakan satuan dari sidebar siswa belum
    $('.btn-petakan-satuan').on('click', function() {
        const sId = $(this).data('siswa-id');
        $('#modalMappingDudi').modal('show');

        // Uncheck all, check this student
        $('.chk-siswa-target').prop('checked', false);
        $('#chkS_' + sId).prop('checked', true).trigger('change');
    });

    // Filter realtime list siswa belum di sidebar kiri
    $('#filterBelumInput').on('keyup input', function() {
        const q = $(this).val().toLowerCase().trim();
        $('#ulSiswaBelum .item-siswa-belum').each(function() {
            const text = $(this).data('search');
            if (text.indexOf(q) > -1) {
                $(this).removeClass('d-none');
            } else {
                $(this).addClass('d-none');
            }
        });
    });

    // Live Search DUDI di modal
    $('#searchDudiInput').on('keyup input', function() {
        const q = $(this).val().toLowerCase().trim();
        $('#dudiCardsContainer .dudi-select-card').each(function() {
            const text = $(this).data('search');
            if (text.indexOf(q) > -1) {
                $(this).removeClass('d-none');
            } else {
                $(this).addClass('d-none');
            }
        });
    });

    // Klik kartu DUDI untuk memilih
    $(document).on('click', '.dudi-select-card', function() {
        $('.dudi-select-card').removeClass('selected-dudi');
        $(this).addClass('selected-dudi');

        const dId = $(this).data('dudi-id');
        const dName = $(this).data('dudi-name');
        const dAlamat = $(this).data('dudi-alamat');
        const dBidang = $(this).data('dudi-bidang');

        currentSelectedDudi = { id: dId, name: dName, alamat: dAlamat, bidang: dBidang };

        $('#selectedDudiId').val(dId);
        $('#selectedDudiName').text(dName);
        $('#selectedDudiDetails').text(dBidang + ' • ' + (dAlamat || 'Alamat belum diatur'));
        $('#selectedDudiBanner').removeClass('d-none');

        updatePlacementSummary();
    });

    // Filter Siswa di Modal berdasarkan Kelas & Search Box
    function filterSiswaInModal() {
        const selectedKelas = $('#filterKelasModal').val();
        const searchKeyword = $('#searchSiswaModal').val().toLowerCase().trim();

        let visibleCount = 0;
        $('#siswaCheckboxContainer .student-chk-item').each(function() {
            const itemKelas = $(this).data('kelas');
            const itemSearch = $(this).data('search');

            let matchKelas = (!selectedKelas || itemKelas === selectedKelas);
            let matchSearch = (!searchKeyword || itemSearch.indexOf(searchKeyword) > -1);

            if (matchKelas && matchSearch) {
                $(this).removeClass('d-none');
                visibleCount++;
            } else {
                $(this).addClass('d-none');
            }
        });

        $('#lblTampilCount').text('Menampilkan ' + visibleCount + ' siswa');
    }

    $('#filterKelasModal').on('change', filterSiswaInModal);
    $('#searchSiswaModal').on('keyup input', filterSiswaInModal);

    // Checkbox Pilih Semua yang Tampil
    $('#chkSelectAllFiltered').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('#siswaCheckboxContainer .student-chk-item:not(.d-none) .chk-siswa-target').prop('checked', isChecked);
        updatePlacementSummary();
    });

    // Checkbox Siswa berubah
    $(document).on('change', '.chk-siswa-target', function() {
        updatePlacementSummary();
    });

    // Perbarui Teks Ringkasan Penempatan & Validasi Tombol Submit
    function updatePlacementSummary() {
        const selectedCount = $('.chk-siswa-target:checked').length;
        $('#badgeTotalSelected').text(selectedCount + ' Siswa Dipilih');

        const $btn = $('#btnSubmitMapping');
        const $summary = $('#footerTotalJam, #footerPlacementSummary');

        if (currentSelectedDudi && selectedCount > 0) {
            $summary.html('Menempatkan <strong class="text-primary">' + selectedCount + ' siswa</strong> ke <strong class="text-success">' + escapeHtml(currentSelectedDudi.name) + '</strong> (PKL 1)');
            $btn.prop('disabled', false);
        } else if (!currentSelectedDudi && selectedCount > 0) {
            $summary.html('<span class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>Pilih salah satu Mitra DUDI di kolom kiri.</span>');
            $btn.prop('disabled', true);
        } else if (currentSelectedDudi && selectedCount === 0) {
            $summary.html('Mitra terpilih: <strong>' + escapeHtml(currentSelectedDudi.name) + '</strong>. Pilih minimal 1 siswa di kolom kanan.');
            $btn.prop('disabled', true);
        } else {
            $summary.text('Silakan pilih DUDI dan minimal 1 siswa.');
            $btn.prop('disabled', true);
        }
    }

    // Modal Pindah DUDI
    $('.btn-pindah-dudi').on('click', function() {
        const mId = $(this).data('mapping-id');
        const sNama = $(this).data('nama-siswa');
        const sKelas = $(this).data('nama-kelas');
        const dId = $(this).data('dudi-id');
        const dNama = $(this).data('nama-dudi');
        const tahap = $(this).data('tahap');

        $('#pindahMappingId').val(mId);
        $('#pindahNamaSiswa').text(sNama);
        $('#pindahNamaKelas').text(sKelas);
        $('#pindahDudiAsal').text(dNama);
        $('#pindahTahapBadge').text(tahap);

        // Disable DUDI yang sama di select tujuan
        $('#pindahDudiTujuanSelect option').prop('disabled', false);
        $('#pindahDudiTujuanSelect option[value="' + dId + '"]').prop('disabled', true);
        $('#pindahDudiTujuanSelect').val('');

        $('#modalPindahDudi').modal('show');
    });

    // Modal Lihat Riwayat
    $(document).on('click', '.btn-view-riwayat', function() {
        const sId = $(this).data('siswa-id');
        $('#modalRiwayatPkl').modal('show');
        $('#riwayatContent').html('<div class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat log riwayat...</div>');

        $.get('<?= site_url('mappingdudi/riwayat') ?>/' + sId, function(res) {
            if (res.status) {
                $('#riwayatModalSub').text(res.siswa.nama + ' (' + res.siswa.nama_kelas + ')');
                
                let html = `
                    <div class="card bg-light border-0 p-3 mb-3">
                        <div class="text-xs text-uppercase font-weight-bold text-success mb-1">Penempatan Aktif Saat Ini:</div>
                        <h6 class="font-weight-bold text-gray-900 mb-0">${escapeHtml(res.penempatan.nama_dudi || '-')}</h6>
                        <div class="text-xs text-muted">${escapeHtml(res.penempatan.alamat_dudi || '-')}</div>
                        <div class="mt-2"><span class="badge badge-success px-2 py-1 font-weight-bold">${escapeHtml(res.penempatan.tahap_pkl || 'PKL 1')}</span></div>
                    </div>
                    <h6 class="font-weight-bold text-gray-900 mb-2"><i class="fas fa-history mr-1 text-warning"></i> Log Perpindahan Mitra DUDI:</h6>
                `;

                if (res.riwayat && res.riwayat.length > 0) {
                    html += '<div class="timeline ml-2">';
                    res.riwayat.forEach(function(r, idx) {
                        html += `
                            <div class="border-left pl-3 pb-3 position-relative" style="border-left: 2px solid #cbd5e1 !important;">
                                <div class="font-weight-bold text-gray-900 text-sm">
                                    <span class="badge badge-warning text-dark mr-1">${escapeHtml(r.tahap_sebelumnya)} &rarr; ${escapeHtml(r.tahap_baru)}</span>
                                    ${escapeHtml(r.nama_dudi_asal || 'DUDI Awal')} &rarr; <strong>${escapeHtml(r.nama_dudi_tujuan || 'DUDI Tujuan')}</strong>
                                </div>
                                <div class="text-xs text-muted mt-1">
                                    <i class="far fa-clock mr-1"></i> ${escapeHtml(r.tanggal_pindah)} &bull; Operator: ${escapeHtml(r.created_by || 'admin')}
                                </div>
                                <div class="alert alert-light border text-xs p-2 mt-1 mb-0">
                                    <strong>Alasan:</strong> ${escapeHtml(r.alasan_pindah || '-')}
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                } else {
                    html += '<p class="text-muted text-sm mb-0">Siswa belum pernah melakukan perpindahan DUDI.</p>';
                }

                $('#riwayatContent').html(html);
            } else {
                $('#riwayatContent').html('<div class="alert alert-danger">' + (res.message || 'Gagal memuat riwayat') + '</div>');
            }
        }).fail(function() {
            $('#riwayatContent').html('<div class="alert alert-danger">Gagal menghubungi server.</div>');
        });
    });

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});
</script>
