<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Distribusi Siswa Bimbingan (Metode Koefisien)</h1>
        <p class="text-muted text-sm mb-0">
            Pilih dan petakan siswa asuh untuk guru pembimbing. Siswa yang disimpan akan <strong>otomatis terisi</strong> pada kolom Pembimbing di menu Mapping DUDI.
        </p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= site_url('distribusimanual') ?>" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Pembimbing
        </a>
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

<!-- Guru Profile & Quota Summary Card -->
<div class="card shadow border-0 mb-4" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); color: #ffffff; border-radius: 12px;">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-3 mb-lg-0">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center font-weight-bold shadow-sm mr-3" style="width: 56px; height: 56px; font-size: 1.4rem;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <div class="text-xs text-white-50 text-uppercase font-weight-bold" style="letter-spacing: 0.05em;">Guru Pembimbing PKL</div>
                        <h4 class="font-weight-bold mb-0 text-white"><?= esc($guru->nama_guru) ?></h4>
                        <div class="text-xs text-white-50 mt-1">
                            NIP: <?= esc($guru->nip ?: '-') ?> &bull; Status: <span class="badge badge-success px-2 py-0">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="bg-white rounded-lg p-3 text-dark shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-xs font-weight-bold text-uppercase text-muted">Realisasi Kuota Guru:</span>
                        <span id="grandStatusBadge" class="badge badge-warning px-2 py-1 font-weight-bold">
                            Menghitung...
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <div class="h4 font-weight-bold text-gray-900 mb-0">
                            <span id="grandSelectedNum" class="text-primary"><?= esc($total_terpilih ?? 0) ?></span>
                            <small class="text-muted font-weight-normal">/ <?= esc($total_jatah ?? 0) ?> Siswa Terpilih</small>
                        </div>
                        <span id="grandPercentText" class="text-xs font-weight-bold text-muted">0%</span>
                    </div>

                    <div class="progress" style="height: 10px; border-radius: 5px;">
                        <div id="grandProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="<?= site_url('distribusimanual/simpan') ?>" method="POST" id="formDistribusiMapping">
    <?= csrf_field() ?>
    <input type="hidden" name="guru_id" value="<?= $guru->id ?>">

    <div class="row">
        <?php if (empty($jam)): ?>
            <div class="col-12">
                <div class="card shadow p-5 text-center text-muted border-0">
                    <i class="fas fa-exclamation-circle fa-4x text-warning mb-3"></i>
                    <h5 class="font-weight-bold text-gray-800">Belum Ada Rombel / Kuota Pembimbing</h5>
                    <p class="mb-0 text-sm">Guru ini belum memiliki pembagian jam mengajar atau kuota pembimbing pada tahun ajaran ini. Silakan atur di menu <strong>Pembagian Jam</strong> atau <strong>Pembimbing (Koefisien)</strong> terlebih dahulu.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($jam as $j): ?>
                <?php 
                    $targetKelas = (int)$j->jatah;
                    $terpilihAwal = count($j->selected);
                ?>
                <div class="col-lg-6 mb-4 class-panel" data-kelas-id="<?= $j->kelas_id ?>" data-target="<?= $targetKelas ?>">
                    <div class="card shadow h-100 border-0 class-card" style="border-radius: 10px;">
                        <!-- Header Kelas -->
                        <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between border-bottom">
                            <div>
                                <h6 class="m-0 font-weight-bold text-primary" style="font-size: 1rem;">
                                    <i class="fas fa-chalkboard mr-1 text-secondary"></i> <?= esc($j->nama_kelas) ?>
                                </h6>
                                <span class="text-xs text-muted">Beban Jam: <strong class="text-dark"><?= esc($j->jumlah_jam) ?> JP</strong></span>
                            </div>
                            <div class="text-right">
                                <span class="badge class-badge-counter px-3 py-2 font-weight-bold shadow-sm" style="font-size: 0.85rem;" data-kelas-id="<?= $j->kelas_id ?>" data-target="<?= $targetKelas ?>">
                                    <span class="count-val"><?= $terpilihAwal ?></span> / <?= $targetKelas ?> Siswa
                                </span>
                            </div>
                        </div>

                        <!-- Class Controls Toolbar -->
                        <div class="p-3 bg-light border-bottom">
                            <div class="input-group input-group-sm mb-2 shadow-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                </div>
                                <input type="text" class="form-control search-siswa-class" data-kelas-id="<?= $j->kelas_id ?>" placeholder="Cari nama siswa / NISN / Mitra DUDI...">
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-xs btn-outline-primary btn-pilih-kuota" data-kelas-id="<?= $j->kelas_id ?>" data-target="<?= $targetKelas ?>" title="Pilih otomatis siswa teratas sebanyak target kuota">
                                        <i class="fas fa-magic mr-1"></i> Pilih Sesuai Kuota (<?= $targetKelas ?>)
                                    </button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary btn-reset-kelas" data-kelas-id="<?= $j->kelas_id ?>" title="Batalkan semua pilihan di kelas ini">
                                        <i class="fas fa-undo mr-1"></i> Batal
                                    </button>
                                </div>
                                <span class="text-xs text-muted lbl-count-visible" data-kelas-id="<?= $j->kelas_id ?>">
                                    <?= count($j->siswa) ?> siswa tersedia
                                </span>
                            </div>
                        </div>

                        <!-- Student Items List -->
                        <div class="card-body p-2" style="max-height: 440px; overflow-y: auto;">
                            <?php if (empty($j->siswa)): ?>
                                <div class="p-4 text-center text-muted">
                                    <i class="fas fa-info-circle mb-2 fa-2x text-secondary"></i>
                                    <p class="mb-0 text-sm">Tidak ada siswa yang tersedia di rombel ini (semua telah dibimbing guru lain).</p>
                                </div>
                            <?php else: ?>
                                <div class="siswa-list-container" data-kelas-id="<?= $j->kelas_id ?>">
                                    <?php foreach ($j->siswa as $s): ?>
                                        <?php 
                                            $isChecked = in_array($s->id, $j->selected); 
                                            $searchString = strtolower(esc($s->nama . ' ' . $s->nisn . ' ' . ($s->nama_dudi ?? '')));
                                        ?>
                                        <div class="card mb-2 student-pick-card border-0 shadow-sm <?= $isChecked ? 'selected-student' : '' ?>" data-search="<?= $searchString ?>" data-siswa-id="<?= $s->id ?>" data-kelas-id="<?= $j->kelas_id ?>" style="cursor: pointer; transition: all 0.15s ease;">
                                            <div class="card-body p-2 px-3">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" name="siswa[<?= $j->kelas_id ?>][]" value="<?= $s->id ?>" class="custom-control-input chk-siswa-manual" id="chk_m_<?= $j->kelas_id ?>_<?= $s->id ?>" data-kelas-id="<?= $j->kelas_id ?>" <?= $isChecked ? 'checked' : '' ?>>
                                                    <label class="custom-control-label w-100 cursor-pointer ml-1" for="chk_m_<?= $j->kelas_id ?>_<?= $s->id ?>" style="cursor: pointer;">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <div class="font-weight-bold text-gray-900 text-sm student-nama"><?= esc($s->nama) ?></div>
                                                                <div class="text-xs text-muted">
                                                                    NISN: <?= esc($s->nisn ?: '-') ?> &bull; 
                                                                    <span class="text-secondary"><?= ($s->jenis_kelamin === 'L') ? '<i class="fas fa-mars text-primary"></i> Laki-laki' : '<i class="fas fa-venus text-danger"></i> Perempuan' ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <?php if (!empty($s->nama_dudi)): ?>
                                                                    <span class="badge badge-light border text-primary" style="font-size: 0.7rem;" title="Mitra DUDI Tempat PKL Siswa">
                                                                        <i class="fas fa-building mr-1"></i><?= esc($s->nama_dudi) ?>
                                                                    </span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-light border text-muted" style="font-size: 0.7rem;" title="Siswa belum ditempatkan ke DUDI">
                                                                        <i class="far fa-clock mr-1"></i>Belum ke DUDI
                                                                    </span>
                                                                <?php endif; ?>
                                                                <div class="chk-status-tag mt-1 <?= $isChecked ? '' : 'd-none' ?>">
                                                                    <span class="badge badge-primary px-2 py-0" style="font-size: 0.68rem;">
                                                                        <i class="fas fa-check mr-1"></i>Asuhan
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (!empty($jam)): ?>
        <!-- Sticky Bottom Floating Action Bar -->
        <div class="card shadow-lg border-0 mb-4 bg-white p-3 sticky-bottom-bar" style="border-radius: 12px; border-left: 5px solid #10b981 !important;">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div class="mb-2 mb-md-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle fa-2x text-primary mr-3"></i>
                        <div>
                            <div class="font-weight-bold text-gray-900" id="footerSummaryTitle">
                                Total Dipilih: <span class="text-primary font-weight-bold" id="footerCountSelected"><?= esc($total_terpilih ?? 0) ?></span> dari <?= esc($total_jatah ?? 0) ?> Siswa Kuota
                            </div>
                            <small class="text-muted d-block" id="footerSummarySubtitle">
                                Klik tombol simpan untuk memperbarui siswa bimbingan dan otomatis mengisi kolom Pembimbing di menu Mapping DUDI.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm" id="btnSubmitDistribusi">
                        <i class="fas fa-save mr-2"></i> Simpan Siswa Bimbingan
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</form>

<style>
.cursor-pointer { cursor: pointer; }
.student-pick-card {
    border: 2px solid transparent !important;
}
.student-pick-card:hover {
    background-color: #f8fafc;
    border-color: #e2e8f0 !important;
}
.student-pick-card.selected-student {
    background-color: #eef2ff !important;
    border-color: #4f46e5 !important;
}
.sticky-bottom-bar {
    position: sticky;
    bottom: 20px;
    z-index: 99;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const totalJatahGuru = <?= (int)($total_jatah ?? 0) ?>;

    // Recalculate Class and Grand Totals
    function refreshCounters() {
        let grandSelected = 0;

        $('.class-panel').each(function() {
            const kId = $(this).data('kelas-id');
            const target = parseInt($(this).data('target')) || 0;
            const selectedCount = $(this).find('.chk-siswa-manual:checked').length;
            grandSelected += selectedCount;

            // Update badge counter per kelas
            const $badge = $(`.class-badge-counter[data-kelas-id="${kId}"]`);
            $badge.find('.count-val').text(selectedCount);

            $badge.removeClass('badge-success badge-warning badge-danger badge-primary');
            if (target > 0 && selectedCount === target) {
                $badge.addClass('badge-success');
                $badge.attr('title', 'Target kuota terpenuhi pas');
            } else if (selectedCount > target && target > 0) {
                $badge.addClass('badge-danger');
                $badge.attr('title', 'Melebihi target kuota!');
            } else {
                $badge.addClass('badge-warning');
                $badge.attr('title', 'Belum mencapai target kuota');
            }
        });

        // Update Grand Totals
        $('#grandSelectedNum').text(grandSelected);
        $('#footerCountSelected').text(grandSelected);

        const pct = totalJatahGuru > 0 ? Math.min(100, Math.round((grandSelected / totalJatahGuru) * 100)) : 0;
        $('#grandPercentText').text(pct + '%');
        $('#grandProgressBar').css('width', pct + '%');

        const $grandBadge = $('#grandStatusBadge');
        $grandBadge.removeClass('badge-success badge-warning badge-danger');

        if (totalJatahGuru > 0 && grandSelected === totalJatahGuru) {
            $grandBadge.addClass('badge-success').html('<i class="fas fa-check-circle mr-1"></i> Kuota Pas Sempurna');
            $('#grandProgressBar').removeClass('bg-warning bg-danger').addClass('bg-success');
        } else if (grandSelected > totalJatahGuru && totalJatahGuru > 0) {
            const over = grandSelected - totalJatahGuru;
            $grandBadge.addClass('badge-danger').html(`<i class="fas fa-exclamation-triangle mr-1"></i> Kelebihan ${over} Siswa`);
            $('#grandProgressBar').removeClass('bg-success bg-warning').addClass('bg-danger');
        } else {
            const sisa = totalJatahGuru - grandSelected;
            $grandBadge.addClass('badge-warning').html(`<i class="fas fa-clock mr-1"></i> Kurang ${sisa} Siswa`);
            $('#grandProgressBar').removeClass('bg-success bg-danger').addClass('bg-warning');
        }
    }

    // Checkbox Change Event
    $(document).on('change', '.chk-siswa-manual', function() {
        const $card = $(this).closest('.student-pick-card');
        const isChecked = $(this).is(':checked');

        if (isChecked) {
            $card.addClass('selected-student');
            $card.find('.chk-status-tag').removeClass('d-none');
        } else {
            $card.removeClass('selected-student');
            $card.find('.chk-status-tag').addClass('d-none');
        }

        refreshCounters();
    });

    // Quick Pick: Top N Students to reach target quota
    $('.btn-pilih-kuota').on('click', function() {
        const kId = $(this).data('kelas-id');
        const target = parseInt($(this).data('target')) || 0;
        const $panel = $(`.class-panel[data-kelas-id="${kId}"]`);

        // First uncheck all in this class
        $panel.find('.chk-siswa-manual').prop('checked', false);
        $panel.find('.student-pick-card').removeClass('selected-student');
        $panel.find('.chk-status-tag').addClass('d-none');

        // Check the top N items
        let count = 0;
        $panel.find('.student-pick-card:not(.d-none)').each(function() {
            if (count < target) {
                const $chk = $(this).find('.chk-siswa-manual');
                $chk.prop('checked', true);
                $(this).addClass('selected-student');
                $(this).find('.chk-status-tag').removeClass('d-none');
                count++;
            }
        });

        refreshCounters();
    });

    // Reset All in Class
    $('.btn-reset-kelas').on('click', function() {
        const kId = $(this).data('kelas-id');
        const $panel = $(`.class-panel[data-kelas-id="${kId}"]`);

        $panel.find('.chk-siswa-manual').prop('checked', false);
        $panel.find('.student-pick-card').removeClass('selected-student');
        $panel.find('.chk-status-tag').addClass('d-none');

        refreshCounters();
    });

    // Live Search per Class
    $('.search-siswa-class').on('keyup input', function() {
        const kId = $(this).data('kelas-id');
        const q = $(this).val().toLowerCase().trim();
        const $panel = $(`.class-panel[data-kelas-id="${kId}"]`);

        let visibleCount = 0;
        $panel.find('.student-pick-card').each(function() {
            const sText = $(this).data('search');
            if (!q || sText.indexOf(q) > -1) {
                $(this).removeClass('d-none');
                visibleCount++;
            } else {
                $(this).addClass('d-none');
            }
        });

        $panel.find('.lbl-count-visible').text(`${visibleCount} siswa ditampilkan`);
    });

    // Initial calculation on load
    refreshCounters();
});
</script>
