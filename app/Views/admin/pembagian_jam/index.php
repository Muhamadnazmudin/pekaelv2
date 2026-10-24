<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Pembagian Jam Mengajar PKL (<?= esc($jp_wajib) ?> JP / Rombel)</h1>
        <p class="text-muted text-sm mb-0">Alokasikan beban jam mengajar guru pembimbing PKL per rombel hingga mencapai total <strong><?= esc($jp_wajib) ?> JP</strong> pas sesuai ketentuan Dapodik.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-primary shadow-sm mr-2" id="btnMappingCepatUtama">
            <i class="fas fa-layer-group mr-1"></i> Mapping Cepat Per Kelas
        </button>
        <button type="button" class="btn btn-outline-secondary shadow-sm" data-toggle="modal" data-target="#modalTambahJam">
            <i class="fas fa-plus mr-1"></i> Tambah Satuan
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

<!-- Status Monitoring 46 JP Tiap Rombel -->
<div class="row mb-4">
    <?php foreach ($recap_kelas as $rk): ?>
        <?php 
            $rkObj = (object)$rk;
            $tot = (int)($rkObj->total_terbagi ?? $rkObj->total_jam ?? 0);
            $namaKelas = $rkObj->nama_kelas ?? '-';
            $sisa = (int)($rkObj->sisa ?? max(0, $jp_wajib - $tot));
            $persen = min(100, round(($tot / max(1, $jp_wajib)) * 100));
            $isPas = $tot == $jp_wajib;
            $isLebih = $tot > $jp_wajib;
        ?>
        <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
            <div class="card shadow-sm h-100 <?= $isPas ? 'border-left-success' : ($isLebih ? 'border-left-danger' : 'border-left-warning') ?>">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="font-weight-bold text-gray-900"><?= esc($namaKelas) ?></span>
                            <?php if ($isPas): ?>
                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> PAS <?= $jp_wajib ?> JP</span>
                            <?php elseif ($isLebih): ?>
                                <span class="badge badge-danger px-2 py-1">LEBIH <?= $tot - $jp_wajib ?> JP</span>
                            <?php else: ?>
                                <span class="badge badge-warning px-2 py-1">KURANG <?= $sisa ?> JP</span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-between text-xs text-muted mb-2">
                            <span>Terbagi: <strong><?= $tot ?> JP</strong></span>
                            <span>Target: <?= $jp_wajib ?> JP</span>
                        </div>
                        <div class="progress progress-sm mb-3">
                            <div class="progress-bar <?= $isPas ? 'bg-success' : ($isLebih ? 'bg-danger' : 'bg-warning') ?>" role="progressbar" style="width: <?= $persen ?>%"></div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-block btn-buka-mapping-kelas" data-kelas-id="<?= $rk->kelas_id ?>" data-nama-kelas="<?= esc($namaKelas) ?>">
                        <i class="fas fa-sliders-h mr-1"></i> Atur Jam Guru
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-clock mr-2"></i> Rincian Pembagian Jam Mengajar PKL</h6>
        <span class="badge badge-light border text-xs"><?= count($jam) ?> Data Alokasi</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 dataTable">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Nama Guru Pembimbing</th>
                        <th>NIP</th>
                        <th>Kelas / Rombel Sasaran</th>
                        <th class="text-center" style="width: 140px;">Jumlah Jam</th>
                        <th class="text-center" style="width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jam)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada pembagian jam mengajar PKL. Silakan klik tombol "Mapping Cepat Per Kelas".</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($jam as $j): ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="font-weight-bold text-gray-900"><?= esc($j->nama_guru) ?></div>
                                </td>
                                <td>
                                    <span class="text-monospace text-secondary"><?= esc($j->nip ?: '-') ?></span>
                                </td>
                                <td>
                                    <span class="badge badge-info px-2 py-1"><?= esc($j->nama_kelas) ?></span>
                                </td>
                                <td class="text-center font-weight-bold text-primary" style="font-size: 1.05rem;">
                                    <?= esc($j->jumlah_jam) ?> JP
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('pembagianjam/hapus/' . $j->id) ?>" class="btn btn-sm btn-outline-danger shadow-sm btn-hapus" title="Hapus Alokasi">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL MAPPING CEPAT PER KELAS (MULTI-GURU DENGAN LIVE JP)      -->
<!-- ============================================================== -->
<div class="modal fade" id="modalMappingKelas" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-layer-group fa-lg mr-2"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0">Mapping Pembagian Jam Mengajar PKL</h5>
                        <small class="text-white-50">Petakan beberapa guru sekaligus (misal: 12 JP, 6 JP, 7 JP, 1 JP...) hingga pas <?= $jp_wajib ?> JP</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            
            <form id="formMappingBulk">
                <input type="hidden" name="<?= csrf_token() ?>" id="csrfBulkToken" value="<?= csrf_hash() ?>">
                <div class="modal-body p-4">
                    <!-- Class Selector Toolbar -->
                    <div class="card bg-light border-0 mb-3 shadow-sm">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-md-7 mb-3 mb-md-0">
                                    <label class="font-weight-bold text-gray-800 mb-1 d-flex align-items-center">
                                        <i class="fas fa-chalkboard-teacher mr-1 text-primary"></i> Pilih Rombel / Kelas Sasaran:
                                    </label>
                                    <div class="input-group">
                                        <select id="bulkKelasSelect" name="kelas_id" class="form-control font-weight-bold text-primary">
                                            <?php foreach ($kelas as $k): ?>
                                                <option value="<?= $k->id ?>"><?= esc($k->nama_kelas) ?> (Tingkat <?= esc($k->tingkat) ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" id="btnPrevKelas" title="Kelas Sebelumnya"><i class="fas fa-chevron-left"></i></button>
                                            <button type="button" class="btn btn-outline-secondary" id="btnNextKelas" title="Kelas Berikutnya"><i class="fas fa-chevron-right"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 text-md-right">
                                    <div class="d-inline-block text-left text-md-right">
                                        <div class="text-xs text-muted text-uppercase font-weight-bold mb-1">Status Alokasi Jam:</div>
                                        <div class="d-flex align-items-center justify-content-md-end">
                                            <span id="bulkStatusBadge" class="badge badge-warning px-3 py-2 font-weight-bold" style="font-size: 0.95rem;">KURANG <?= $jp_wajib ?> JP</span>
                                            <div class="ml-3 font-weight-bold text-gray-900" style="font-size: 1.15rem;">
                                                <span id="bulkTotalJam" class="text-primary">0</span> / <?= $jp_wajib ?> JP
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="progress progress-sm mt-3" style="height: 8px;">
                                <div id="bulkProgressBar" class="progress-bar bg-warning" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Teacher List Container -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="font-weight-bold text-gray-800 mb-0">Daftar Guru Pembimbing & Alokasi JP:</label>
                        <span class="text-xs text-muted"><i class="fas fa-info-circle mr-1"></i>Masukkan JP per guru. Total harus pas <strong><?= $jp_wajib ?> JP</strong>.</span>
                    </div>

                    <div class="table-responsive border rounded mb-2" style="max-height: 340px; overflow-y: auto;">
                        <table class="table table-bordered table-sm align-middle mb-0" id="tableBulkRows">
                            <thead class="thead-light sticky-top">
                                <tr>
                                    <th style="width: 45px;" class="text-center">#</th>
                                    <th>Pilih Guru Pembimbing</th>
                                    <th style="width: 170px;" class="text-center">Alokasi Jam (JP)</th>
                                    <th style="width: 50px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="bulkRowsBody">
                                <!-- Dynamic rows via JS -->
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <button type="button" class="btn btn-outline-primary btn-sm font-weight-bold" id="btnAddTeacherRow">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Guru Pembimbing
                        </button>
                        <button type="button" class="btn btn-link btn-sm text-danger p-0" id="btnClearRows">
                            <i class="fas fa-trash-alt mr-1"></i> Kosongkan Daftar
                        </button>
                    </div>
                </div>

                <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-sm text-muted">
                            Total Jam Terisi: <strong id="footerTotalJam" class="text-primary font-weight-bold">0 JP</strong>
                        </span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary mr-1" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary font-weight-bold mr-1" id="btnSaveBulk">
                            <i class="fas fa-save mr-1"></i> Simpan Alokasi Kelas Ini
                        </button>
                        <button type="button" class="btn btn-success font-weight-bold" id="btnSaveAndNext">
                            <i class="fas fa-forward mr-1"></i> Simpan & Lanjut Kelas Berikutnya
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Satuan (Lama / Fallback) -->
<div class="modal fade" id="modalTambahJam" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i> Tambah Alokasi Jam Satuan</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('pembagianjam/simpan') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Pilih Guru Pembimbing</label>
                        <select name="guru_id" class="form-control" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php foreach ($guru as $g): ?>
                                <option value="<?= $g->id ?>"><?= esc($g->nama_guru) ?> (NIP: <?= esc($g->nip ?: '-') ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Pilih Kelas / Rombel Sasaran</label>
                        <select name="kelas_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelas as $k): ?>
                                <option value="<?= $k->id ?>"><?= esc($k->nama_kelas) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-gray-700">Jumlah Jam (JP)</label>
                        <div class="input-group">
                            <input type="number" name="jumlah_jam" class="form-control" min="1" max="46" placeholder="Contoh: 12" required>
                            <div class="input-group-append">
                                <span class="input-group-text font-weight-bold">JP</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">Total alokasi semua guru di rombel yang sama harus pas <strong><?= $jp_wajib ?> JP</strong>.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-secondary font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Master data guru untuk dropdown
    const guruMasterList = <?= json_encode(array_map(function($g) {
        return [
            'id'   => (int)$g->id,
            'nama' => $g->nama_guru,
            'nip'  => $g->nip ?: '-'
        ];
    }, $guru)) ?>;

    const targetJp = <?= (int)$jp_wajib ?>;
    let isDataChanged = false;

    // Helper escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Helper buat satu baris guru
    function createTeacherRow(selectedGuruId = '', jamValue = '') {
        let options = '<option value="">-- Pilih Guru --</option>';
        guruMasterList.forEach(function(g) {
            const isSel = (String(g.id) === String(selectedGuruId)) ? 'selected' : '';
            options += `<option value="${g.id}" ${isSel}>${escapeHtml(g.nama)} (NIP: ${escapeHtml(g.nip)})</option>`;
        });

        return `
            <tr class="bulk-row">
                <td class="text-center text-muted font-weight-bold row-num"></td>
                <td>
                    <select name="guru_id[]" class="form-control form-control-sm guru-select" required>
                        ${options}
                    </select>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" name="jumlah_jam[]" class="form-control form-control-sm jp-input font-weight-bold text-center text-primary" min="1" max="${targetJp}" placeholder="Contoh: 12" value="${jamValue}" required>
                        <div class="input-group-append">
                            <span class="input-group-text font-weight-bold">JP</span>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-del-row" title="Hapus guru ini"><i class="fas fa-times"></i></button>
                </td>
            </tr>
        `;
    }

    // Hitung ulang total jam secara real-time
    function recalculateBulk() {
        let total = 0;
        $('#bulkRowsBody .bulk-row').each(function(idx) {
            $(this).find('.row-num').text(idx + 1);
            const val = parseInt($(this).find('.jp-input').val()) || 0;
            total += val;
        });

        const sisa = targetJp - total;
        const persen = Math.min(100, Math.round((total / targetJp) * 100));

        $('#bulkTotalJam').text(total);
        $('#footerTotalJam').text(total + ' JP');

        const $badge = $('#bulkStatusBadge');
        const $bar = $('#bulkProgressBar');

        $bar.css('width', persen + '%');

        if (total === targetJp) {
            $badge.removeClass('badge-warning badge-danger').addClass('badge-success').html('<i class="fas fa-check-circle mr-1"></i> PAS ' + targetJp + ' JP');
            $bar.removeClass('bg-warning bg-danger').addClass('bg-success');
        } else if (total > targetJp) {
            const lebih = total - targetJp;
            $badge.removeClass('badge-success badge-warning').addClass('badge-danger').text('LEBIH ' + lebih + ' JP');
            $bar.removeClass('bg-success bg-warning').addClass('bg-danger');
        } else {
            $badge.removeClass('badge-success badge-danger').addClass('badge-warning').text('KURANG ' + sisa + ' JP');
            $bar.removeClass('bg-success badge-danger').addClass('bg-warning');
        }
    }

    // Ambil data alokasi kelas via AJAX
    function loadClassAllocation(kelasId) {
        $('#bulkRowsBody').html('<tr><td colspan="4" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat alokasi jam rombel...</td></tr>');
        
        $.get('<?= site_url('pembagianjam/get_kelas_allocation') ?>/' + kelasId, function(res) {
            if (res.status) {
                $('#bulkRowsBody').empty();
                if (res.allocations && res.allocations.length > 0) {
                    res.allocations.forEach(function(a) {
                        $('#bulkRowsBody').append(createTeacherRow(a.guru_id, a.jumlah_jam));
                    });
                } else {
                    // Default 4 baris kosong siap diisi cepat (misal: 12, 6, 7, 1...)
                    for (let i = 0; i < 4; i++) {
                        $('#bulkRowsBody').append(createTeacherRow('', ''));
                    }
                }
                recalculateBulk();
            } else {
                Swal.fire('Perhatian', res.message || 'Gagal memuat alokasi kelas.', 'warning');
            }
        }).fail(function() {
            Swal.fire('Error', 'Gagal terhubung ke server.', 'error');
        });
    }

    // Buka Modal dari tombol utama
    $('#btnMappingCepatUtama').on('click', function() {
        const firstKelasId = $('#bulkKelasSelect').val();
        $('#modalMappingKelas').modal('show');
        loadClassAllocation(firstKelasId);
    });

    // Buka Modal dari kartu kelas spesifik
    $('.btn-buka-mapping-kelas').on('click', function() {
        const kId = $(this).data('kelas-id');
        $('#bulkKelasSelect').val(kId);
        $('#modalMappingKelas').modal('show');
        loadClassAllocation(kId);
    });

    // Event saat dropdown kelas berubah
    $('#bulkKelasSelect').on('change', function() {
        loadClassAllocation($(this).val());
    });

    // Tombol Kelas Sebelumnya
    $('#btnPrevKelas').on('click', function() {
        const $select = $('#bulkKelasSelect');
        const $opts = $select.find('option');
        const curIdx = $opts.filter(':selected').index();
        if (curIdx > 0) {
            $select.val($opts.eq(curIdx - 1).val()).trigger('change');
        } else {
            $select.val($opts.last().val()).trigger('change');
        }
    });

    // Tombol Kelas Berikutnya
    $('#btnNextKelas').on('click', function() {
        const $select = $('#bulkKelasSelect');
        const $opts = $select.find('option');
        const curIdx = $opts.filter(':selected').index();
        if (curIdx < $opts.length - 1) {
            $select.val($opts.eq(curIdx + 1).val()).trigger('change');
        } else {
            $select.val($opts.first().val()).trigger('change');
        }
    });

    // Tambah Baris Guru Baru
    $('#btnAddTeacherRow').on('click', function() {
        $('#bulkRowsBody').append(createTeacherRow('', ''));
        recalculateBulk();
    });

    // Hapus Baris Guru
    $(document).on('click', '.btn-del-row', function() {
        $(this).closest('tr').remove();
        if ($('#bulkRowsBody .bulk-row').length === 0) {
            $('#bulkRowsBody').append(createTeacherRow('', ''));
        }
        recalculateBulk();
    });

    // Kosongkan Semua Baris
    $('#btnClearRows').on('click', function() {
        if (confirm('Kosongkan semua daftar alokasi guru untuk kelas ini?')) {
            $('#bulkRowsBody').empty();
            $('#bulkRowsBody').append(createTeacherRow('', ''));
            recalculateBulk();
        }
    });

    // Event input jam realtime
    $(document).on('input keyup change', '.jp-input', function() {
        recalculateBulk();
    });

    // Eksekusi Simpan Bulk
    function executeSaveBulk(advanceToNext = false) {
        const kelasId = $('#bulkKelasSelect').val();
        const namaKelas = $('#bulkKelasSelect option:selected').text();
        const guruIds = [];
        const jamList = [];

        $('#bulkRowsBody .bulk-row').each(function() {
            const gid = $(this).find('.guru-select').val();
            const jam = $(this).find('.jp-input').val();
            if (gid && parseInt(jam) > 0) {
                guruIds.push(gid);
                jamList.push(jam);
            }
        });

        // Tampilkan loading di tombol
        const $btnSave = $('#btnSaveBulk');
        const $btnNext = $('#btnSaveAndNext');
        const origSaveHtml = $btnSave.html();
        const origNextHtml = $btnNext.html();

        $btnSave.prop('disabled', true);
        $btnNext.prop('disabled', true);
        if (advanceToNext) {
            $btnNext.html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');
        } else {
            $btnSave.html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');
        }

        const csrfName = '<?= csrf_token() ?>';
        const csrfVal = $('#csrfBulkToken').val();

        const postData = {
            [csrfName]: csrfVal,
            kelas_id: kelasId,
            guru_id: guruIds,
            jumlah_jam: jamList
        };

        $.post('<?= site_url('pembagianjam/simpan_bulk') ?>', postData, function(res) {
            $btnSave.prop('disabled', false).html(origSaveHtml);
            $btnNext.prop('disabled', false).html(origNextHtml);

            if (res.csrf_token) {
                $('#csrfBulkToken').val(res.csrf_token);
            }

            if (res.status) {
                isDataChanged = true;
                
                // Toast sukses
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: res.is_pas ? 'success' : 'info',
                    title: res.message
                });

                if (advanceToNext) {
                    // Pindah ke kelas berikutnya otomatis!
                    $('#btnNextKelas').trigger('click');
                } else {
                    // Refresh data alokasi kelas ini
                    loadClassAllocation(kelasId);
                }
            } else {
                Swal.fire('Gagal Menyimpan', res.message || 'Terjadi kesalahan sistem.', 'error');
            }
        }).fail(function() {
            $btnSave.prop('disabled', false).html(origSaveHtml);
            $btnNext.prop('disabled', false).html(origNextHtml);
            Swal.fire('Error', 'Gagal mengirim data ke server.', 'error');
        });
    }

    // Klik tombol simpan alokasi kelas ini
    $('#btnSaveBulk').on('click', function() {
        executeSaveBulk(false);
    });

    // Klik tombol simpan dan lanjut ke kelas berikutnya
    $('#btnSaveAndNext').on('click', function() {
        executeSaveBulk(true);
    });

    // Reload halaman jika ada data yang diubah saat modal ditutup agar tabel utama sinkron
    $('#modalMappingKelas').on('hidden.bs.modal', function () {
        if (isDataChanged) {
            window.location.reload();
        }
    });
});
</script>
