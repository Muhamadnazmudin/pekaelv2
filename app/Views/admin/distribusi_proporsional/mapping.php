<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Pemilihan Siswa Bimbingan (Metode Proporsional)</h1>
        <p class="text-muted text-sm mb-0">Guru Pembimbing: <strong class="text-primary"><?= esc($guru->nama_guru) ?></strong> (NIP: <?= esc($guru->nip ?: '-') ?>)</p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="<?= site_url('distribusi_proporsional') ?>" class="btn btn-outline-secondary btn-sm shadow-sm">
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

<form action="<?= site_url('distribusi_proporsional/simpan') ?>" method="POST">
    <?= csrf_field() ?>
    <input type="hidden" name="guru_id" value="<?= $guru->id ?>">

    <div class="row">
        <?php if (empty($jam)): ?>
            <div class="col-12">
                <div class="card shadow p-4 text-center text-muted">
                    <i class="fas fa-exclamation-circle fa-3x text-warning mb-2"></i>
                    <p class="mb-0">Guru ini belum memiliki alokasi kuota proporsional di kelas manapun. Silakan generate kuota di menu Pembimbing Kelas terlebih dahulu.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($jam as $j): ?>
                <?php 
                    $terpilihCount = count($j->selected);
                    $jatah = (int)$j->jatah;
                    $isPas = $jatah > 0 && $terpilihCount == $jatah;
                ?>
                <div class="col-lg-6 mb-4">
                    <div class="card shadow h-100 <?= $isPas ? 'border-left-success' : 'border-left-primary' ?>">
                        <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="m-0 font-weight-bold text-primary"><?= esc($j->nama_kelas) ?></h6>
                                <span class="text-xs text-muted">Beban Jam: <?= esc($j->total_jam) ?> JP</span>
                            </div>
                            <span class="badge <?= $isPas ? 'badge-success' : 'badge-warning' ?> px-3 py-2 font-weight-bold" style="font-size: 0.85rem;">
                                Target Kuota: <?= $jatah ?> Siswa (Terpilih: <?= $terpilihCount ?>)
                            </span>
                        </div>
                        <div class="card-body p-3" style="max-height: 420px; overflow-y: auto;">
                            <?php if (empty($j->siswa)): ?>
                                <p class="text-muted text-center py-4 mb-0 text-sm">Tidak ada siswa yang tersedia di kelas ini.</p>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($j->siswa as $s): ?>
                                        <?php $checked = in_array($s->id, $j->selected); ?>
                                        <label class="list-group-item list-group-item-action d-flex align-items-center py-2 px-3 mb-1 rounded border-0 <?= $checked ? 'bg-primary-light' : '' ?>" style="cursor: pointer;">
                                            <input type="checkbox" name="siswa[<?= $j->kelas_id ?>][]" value="<?= $s->id ?>" class="mr-3" style="transform: scale(1.2);" <?= $checked ? 'checked' : '' ?>>
                                            <div class="flex-grow-1">
                                                <div class="font-weight-bold text-gray-900 text-sm"><?= esc($s->nama) ?></div>
                                                <div class="text-xs text-muted">NISN: <?= esc($s->nisn) ?> &bull; JK: <?= esc($s->jenis_kelamin) ?></div>
                                            </div>
                                            <?php if ($checked): ?>
                                                <span class="badge badge-primary px-2 py-1"><i class="fas fa-check mr-1"></i> Asuhan</span>
                                            <?php endif; ?>
                                        </label>
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
        <div class="card shadow-sm border-0 mb-4 bg-white p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-sm font-weight-bold text-gray-800">Pastikan jumlah centang siswa telah sesuai dengan kuota pembimbing guru.</span>
                </div>
                <button type="submit" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm">
                    <i class="fas fa-save mr-1"></i> Simpan Pilihan Siswa Asuh
                </button>
            </div>
        </div>
    <?php endif; ?>
</form>
