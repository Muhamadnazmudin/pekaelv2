<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Pengaturan Sistem & Pemeliharaan Database</h1>
        <p class="text-muted text-sm mb-0">Konfigurasi parameter alokasi JJM PKL, pemilihan metode perhitungan, dan utilitas pembersihan database.</p>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-lg mb-4" role="alert">
        <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-lg mb-4" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Form Parameter & Pemilihan Metode JJM -->
    <div class="col-lg-12 mb-4">
        <div class="card shadow-sm border-0 rounded-xl overflow-hidden">
            <div class="card-header py-3 bg-white border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-sliders-h mr-2"></i> Parameter Sistem &amp; Pilihan Metode Perhitungan PKL
                </h6>
                <span class="badge badge-light border text-xs">Konfigurasi Inti</span>
            </div>
            <div class="card-body p-4">
                <form action="<?= site_url('pengaturan/simpan') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-800 text-sm">Nama Aplikasi</label>
                            <input type="text" name="nama_aplikasi" class="form-control" value="<?= esc($nama_app) ?>" required>
                            <small class="form-text text-muted">Nama brand aplikasi yang tampil di header dan footer.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-800 text-sm">Standar Beban Jam PKL (Kurikulum SMK)</label>
                            <div class="input-group">
                                <input type="number" name="jam_pkl" class="form-control font-weight-bold" value="<?= esc($jam_pkl) ?>" min="1" max="100" required>
                                <div class="input-group-append">
                                    <span class="input-group-text font-weight-bold bg-light">JP / Rombel</span>
                                </div>
                            </div>
                            <small class="form-text text-muted">Standar kurikulum nasional untuk beban mengajar PKL rombel adalah <strong>46 JP</strong>.</small>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Pemilihan Metode Perhitungan PKL -->
                    <label class="font-weight-bold text-gray-900 text-base d-block mb-1">
                        <i class="fas fa-calculator text-primary mr-1"></i> Pilih Metode Perhitungan JJM &amp; Pembimbing PKL:
                    </label>
                    <p class="text-muted text-xs mb-3">
                        Pilihan ini akan otomatis menyesuaikan alur menu perhitungan, alokasi pembimbing, distribusi siswa, dan format laporan di sidebar:
                    </p>

                    <div class="row mb-3">
                        <!-- Opsi 1: Koefisien Konversi -->
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="card h-100 border p-3 rounded-xl cursor-pointer method-choice-card <?= ($metode_aktif === 'koefisien') ? 'active-choice' : '' ?>" style="cursor: pointer; transition: all 0.2s;">
                                <div class="d-flex align-items-start">
                                    <input type="radio" name="metode_perhitungan" value="koefisien" <?= ($metode_aktif === 'koefisien') ? 'checked' : '' ?> class="mt-1 mr-3">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="font-weight-bold text-gray-900" style="font-size: 1rem;">Metode 1: Koefisien Konversi</span>
                                            <span class="badge badge-primary ml-2 px-2 py-0.5 text-xs font-weight-bold">Bagi Rata</span>
                                        </div>
                                        <p class="text-muted text-xs mb-2">
                                            Menghitung angka koefisien seragam sekolah: <code>(Jumlah Rombel &times; 46 JP) / Total Siswa</code>. Guru dialokasikan kuota siswa berdasarkan jumlah jam yang diampu dikalikan koefisien.
                                        </p>
                                        <span class="text-xs text-primary font-weight-600">
                                            <i class="fas fa-check-circle mr-1"></i> Mengaktifkan menu Koefisien PKL &amp; Distribusi Metode Koef
                                        </span>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Opsi 2: Proporsional Per Rombel -->
                        <div class="col-md-6">
                            <label class="card h-100 border p-3 rounded-xl cursor-pointer method-choice-card <?= ($metode_aktif === 'proporsional') ? 'active-choice' : '' ?>" style="cursor: pointer; transition: all 0.2s;">
                                <div class="d-flex align-items-start">
                                    <input type="radio" name="metode_perhitungan" value="proporsional" <?= ($metode_aktif === 'proporsional') ? 'checked' : '' ?> class="mt-1 mr-3">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="font-weight-bold text-gray-900" style="font-size: 1rem;">Metode 2: Proporsional Kelas</span>
                                            <span class="badge badge-info ml-2 px-2 py-0.5 text-xs font-weight-bold">Per Rombel</span>
                                        </div>
                                        <p class="text-muted text-xs mb-2">
                                            Membagi kuota siswa murni per rombel kelas masing-masing secara proporsional sesuai jam mengajar guru di tiap kelas tanpa angka koefisien global sekolah.
                                        </p>
                                        <span class="text-xs text-info font-weight-600">
                                            <i class="fas fa-check-circle mr-1"></i> Mengaktifkan menu Pembimbing Proporsional &amp; Distribusi Prop
                                        </span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Simpan Parameter &amp; Metode
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Section Utilitas Pembersihan Database (Clear Database) -->
    <div class="col-lg-12">
        <div class="card shadow-sm border-0 rounded-xl overflow-hidden">
            <div class="card-header py-3 bg-white border-bottom d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div>
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-broom mr-2"></i> Utilitas Pembersihan Database (Reset Data Selektif)
                    </h6>
                    <small class="text-muted">Bersihkan data transaksi, siswa, absensi, atau master tanpa perlu bolak-balik ke phpMyAdmin.</small>
                </div>
                <div class="mt-2 mt-md-0 d-flex align-items-center gap-2">
                    <span class="badge badge-light border text-dark px-2.5 py-1.5 font-weight-bold" id="badgeSelectedCount">0 Tabel Terpilih</span>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Bar Tindakan Cepat (Quick Select) -->
                <div class="p-3 mb-4 rounded-xl d-flex flex-wrap align-items-center justify-content-between" style="background: rgba(239, 68, 68, 0.05); border: 1px dashed rgba(239, 68, 68, 0.3);">
                    <div class="d-flex flex-wrap align-items-center mb-2 mb-md-0" style="gap: 8px;">
                        <span class="text-xs font-weight-bold text-muted mr-1">PILIH CEPAT:</span>
                        <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold" id="btnCheckAll">
                            <i class="fas fa-check-double mr-1"></i> Centang Semua
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold" id="btnCheckPklOnly">
                            <i class="fas fa-tasks mr-1"></i> Operasional PKL Saja
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold" id="btnUncheckAll">
                            <i class="fas fa-times mr-1"></i> Batal Semua
                        </button>
                    </div>
                    <div class="text-xs text-danger font-weight-bold">
                        <i class="fas fa-shield-alt mr-1 text-success"></i> Akun Administrator Utama (mengadmin) DIJAMIN TETAP AMAN.
                    </div>
                </div>

                <form id="formClearDatabase" action="<?= site_url('pengaturan/reset_data') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row">
                        <?php foreach ($tablesData as $catKey => $cat): ?>
                            <div class="col-lg-4 mb-4">
                                <div class="card h-100 border rounded-xl overflow-hidden shadow-none">
                                    <div class="card-header py-2.5 bg-light d-flex align-items-center justify-content-between">
                                        <span class="font-weight-bold text-xs text-uppercase text-gray-700" style="letter-spacing: 0.05em;">
                                            <?= esc($cat['label']) ?>
                                        </span>
                                        <button type="button" class="btn btn-xs btn-link text-primary font-weight-bold p-0 btn-select-category" data-category="<?= $catKey ?>" style="font-size: 0.75rem;">
                                            Pilih Kategori
                                        </button>
                                    </div>
                                    <div class="card-body p-2" style="max-height: 480px; overflow-y: auto;">
                                        <?php foreach ($cat['items'] as $tblName => $item): ?>
                                            <label class="d-flex align-items-start p-2.5 mb-1 rounded border table-checkbox-card" style="cursor: pointer; transition: all 0.15s; background: #fff;">
                                                <div class="custom-control custom-checkbox mt-1 mr-2">
                                                    <input type="checkbox" name="tables[]" value="<?= $tblName ?>" class="custom-control-input chk-table cat-<?= $catKey ?>" id="chk_<?= $tblName ?>">
                                                    <span class="custom-control-label"></span>
                                                </div>
                                                <div style="flex: 1;">
                                                    <div class="d-flex align-items-center justify-content-between mb-0.5">
                                                        <span class="font-weight-bold text-gray-900 text-xs">
                                                            <i class="<?= $item['icon'] ?> text-<?= $item['badge'] ?> mr-1.5" style="width: 14px;"></i>
                                                            <?= esc($item['nama']) ?>
                                                        </span>
                                                        <span class="badge badge-light border text-xs font-weight-bold">
                                                            <?= number_format($item['count']) ?> baris
                                                        </span>
                                                    </div>
                                                    <div class="text-muted" style="font-size: 0.72rem; line-height: 1.3;">
                                                        <?= $item['deskripsi'] ?>
                                                    </div>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Tombol Aksi Bersihkan -->
                    <div class="p-3.5 bg-light rounded-xl border d-flex flex-column flex-md-row align-items-center justify-content-between mt-2">
                        <div class="mb-3 mb-md-0 text-center text-md-left">
                            <span class="font-weight-bold text-gray-900 d-block">Sudah memeriksa tabel yang Anda centang?</span>
                            <span class="text-xs text-muted">Aksi pembersihan tidak dapat dibatalkan, namun akun login Admin utama tidak akan dihapus.</span>
                        </div>
                        <div>
                            <button type="button" id="btnTriggerClear" class="btn btn-danger btn-lg px-4 shadow font-weight-bold">
                                <i class="fas fa-trash-alt mr-2"></i> Bersihkan Database Terpilih
                            </button>
                        </div>
                    </div>

                    <!-- Modal Konfirmasi Keamanan -->
                    <div class="modal fade" id="modalConfirmClear" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content border-0 shadow-lg rounded-xl">
                                <div class="modal-header bg-danger text-white py-3">
                                    <h6 class="modal-title font-weight-bold">
                                        <i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi Pembersihan Database
                                    </h6>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="text-center mb-3">
                                        <div class="d-inline-flex align-items-center justify-content-center bg-danger-light text-danger rounded-circle mb-2" style="width: 56px; height: 56px; font-size: 1.5rem; background: rgba(239, 68, 68, 0.1);">
                                            <i class="fas fa-trash-alt"></i>
                                        </div>
                                        <h5 class="font-weight-bold text-gray-900">Apakah Anda Yakin Ingin Menghapus?</h5>
                                        <p class="text-muted text-xs mb-0">
                                            Anda akan membersihkan total <strong id="modalTableCount" class="text-danger">0</strong> tabel database berikut:
                                        </p>
                                    </div>

                                    <div class="bg-light p-3 rounded border mb-3" style="max-height: 180px; overflow-y: auto;">
                                        <ul class="mb-0 pl-3 text-xs font-weight-bold" id="modalTableList">
                                            <!-- Dynamically injected -->
                                        </ul>
                                    </div>

                                    <div class="alert alert-info border-0 text-xs mb-0" style="background-color: #eff6ff; color: #1e40af;">
                                        <i class="fas fa-shield-alt mr-1"></i>
                                        <strong>Pengingat Aman:</strong> Akun Administrator Utama (<code>mengadmin</code> / ID 1) <strong>tetap dipertahankan</strong> sehingga Anda tidak akan terkunci dari aplikasi.
                                    </div>
                                </div>
                                <div class="modal-footer bg-light py-2.5">
                                    <button type="button" class="btn btn-sm btn-secondary px-3 font-weight-bold" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-sm btn-danger px-4 shadow font-weight-bold">
                                        <i class="fas fa-broom mr-1"></i> Ya, Bersihkan Sekarang!
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.table-checkbox-card:hover {
    border-color: #ef4444 !important;
    background-color: rgba(239, 68, 68, 0.02) !important;
}
.method-choice-card:hover {
    border-color: #4f46e5 !important;
}
.method-choice-card.active-choice {
    border: 2px solid #4f46e5 !important;
    background: rgba(79, 70, 229, 0.03);
}
.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #dc2626 !important;
    border-color: #dc2626 !important;
}
body.theme-dark .table-checkbox-card {
    background-color: #1e293b !important;
    border-color: #334155 !important;
}
body.theme-dark .table-checkbox-card:hover {
    background-color: #334155 !important;
    border-color: #ef4444 !important;
}
body.theme-dark .method-choice-card {
    background-color: #1e293b !important;
    border-color: #334155 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.chk-table');
    const badgeSelected = document.getElementById('badgeSelectedCount');
    const btnCheckAll = document.getElementById('btnCheckAll');
    const btnCheckPklOnly = document.getElementById('btnCheckPklOnly');
    const btnUncheckAll = document.getElementById('btnUncheckAll');
    const btnTriggerClear = document.getElementById('btnTriggerClear');
    const categoryButtons = document.querySelectorAll('.btn-select-category');

    // Visual selection for method cards
    const methodCards = document.querySelectorAll('.method-choice-card');
    methodCards.forEach(card => {
        card.addEventListener('click', function() {
            methodCards.forEach(c => c.classList.remove('active-choice'));
            this.classList.add('active-choice');
        });
    });

    function updateCount() {
        let count = 0;
        checkboxes.forEach(chk => {
            if (chk.checked) count++;
        });
        badgeSelected.textContent = count + ' Tabel Terpilih';
        if (count > 0) {
            badgeSelected.classList.remove('badge-light', 'text-dark');
            badgeSelected.classList.add('badge-danger', 'text-white');
        } else {
            badgeSelected.classList.remove('badge-danger', 'text-white');
            badgeSelected.classList.add('badge-light', 'text-dark');
        }
    }

    checkboxes.forEach(chk => {
        chk.addEventListener('change', updateCount);
    });

    btnCheckAll.addEventListener('click', function() {
        checkboxes.forEach(chk => chk.checked = true);
        updateCount();
    });

    btnCheckPklOnly.addEventListener('click', function() {
        checkboxes.forEach(chk => {
            chk.checked = chk.classList.contains('cat-operasional');
        });
        updateCount();
    });

    btnUncheckAll.addEventListener('click', function() {
        checkboxes.forEach(chk => chk.checked = false);
        updateCount();
    });

    categoryButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const cat = this.getAttribute('data-category');
            const catChecks = document.querySelectorAll('.cat-' + cat);
            const allChecked = Array.from(catChecks).every(c => c.checked);
            catChecks.forEach(c => c.checked = !allChecked);
            updateCount();
        });
    });

    btnTriggerClear.addEventListener('click', function() {
        const checkedList = [];
        checkboxes.forEach(chk => {
            if (chk.checked) {
                const labelText = chk.closest('.table-checkbox-card').querySelector('span.font-weight-bold').innerText.trim();
                checkedList.push(labelText + ' (' + chk.value + ')');
            }
        });

        if (checkedList.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Belum Ada Tabel Dipilih',
                text: 'Silakan beri tanda centang minimal pada 1 tabel yang ingin dibersihkan.',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        document.getElementById('modalTableCount').textContent = checkedList.length;
        const ul = document.getElementById('modalTableList');
        ul.innerHTML = '';
        checkedList.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item;
            ul.appendChild(li);
        });

        $('#modalConfirmClear').modal('show');
    });
});
</script>
