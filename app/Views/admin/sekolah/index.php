<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Profil Sekolah</h1>
        <p class="text-muted text-sm mb-0">Kelola identitas satuan pendidikan, kepala sekolah, dan logo resmi untuk kop laporan PKL.</p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="<?= site_url('dapodik/tarik_sekolah') ?>" class="btn btn-outline-success btn-sm shadow-sm" onclick="return confirm('Tarik pembaruan data profil sekolah dari Dapodik?');">
            <i class="fas fa-sync-alt mr-1"></i> Tarik Profil dari Dapodik
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

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-school mr-2"></i> Formulir Data Identitas Sekolah</h6>
                <span class="badge badge-light border text-xs">ID Profil: #<?= $sekolah->id ?? 1 ?></span>
            </div>
            <div class="card-body p-4">
                <form action="<?= site_url('sekolah/update') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $sekolah->id ?? 1 ?>">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold text-gray-700">Nama Sekolah</label>
                        <div class="col-sm-9">
                            <input type="text" name="nama_sekolah" class="form-control" value="<?= esc($sekolah->nama_sekolah ?? '') ?>" required placeholder="Contoh: SMKN 1 KOTA CONTOH">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold text-gray-700">NPSN</label>
                        <div class="col-sm-9">
                            <input type="text" name="npsn" class="form-control" value="<?= esc($sekolah->npsn ?? '') ?>" required placeholder="8 Digit Nomor Pokok Sekolah Nasional">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold text-gray-700">Alamat Lengkap</label>
                        <div class="col-sm-9">
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat jalan, kelurahan, kecamatan, kabupaten/kota, provinsi"><?= esc($sekolah->alamat ?? '') ?></textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold text-gray-700">Nama Kepala Sekolah</label>
                        <div class="col-sm-9">
                            <input type="text" name="kepala_sekolah" class="form-control" value="<?= esc($sekolah->kepala_sekolah ?? '') ?>" placeholder="Nama lengkap dengan gelar akademis">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold text-gray-700">NIP Kepala Sekolah</label>
                        <div class="col-sm-9">
                            <input type="text" name="nip_kepsek" class="form-control" value="<?= esc($sekolah->nip_kepsek ?? '') ?>" placeholder="19XXXXXXXXXXXXXX atau tanda strip (-)">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold text-gray-700">Logo Sekolah</label>
                        <div class="col-sm-9">
                            <div class="custom-file">
                                <input type="file" name="logo" class="custom-file-input" id="logoUpload" accept="image/png, image/jpeg">
                                <label class="custom-file-label" for="logoUpload">Pilih file logo (PNG / JPG)...</label>
                            </div>
                            <small class="form-text text-muted">Format PNG/JPG transparan direkomendasikan untuk kop surat dan lembar laporan.</small>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-image mr-2"></i> Preview Logo Sekolah</h6>
            </div>
            <div class="card-body text-center p-4">
                <div class="p-3 border rounded bg-light mb-3 d-inline-block">
                    <?php if (!empty($sekolah->logo) && file_exists(ROOTPATH . 'uploads/logo/' . $sekolah->logo)): ?>
                        <img src="<?= base_url('uploads/logo/' . $sekolah->logo) ?>" alt="Logo Sekolah" class="img-fluid" style="max-height: 140px;">
                    <?php else: ?>
                        <img src="<?= base_url('assets/img/logo.png') ?>" alt="Default Logo" class="img-fluid" style="max-height: 140px;" onerror="this.src='https://ui-avatars.com/api/?name=SMK&size=140&background=4e73df&color=fff'">
                    <?php endif; ?>
                </div>
                <h6 class="font-weight-bold text-gray-900 mb-1"><?= esc($sekolah->nama_sekolah ?? 'Nama Sekolah') ?></h6>
                <p class="text-muted text-xs mb-0">NPSN: <?= esc($sekolah->npsn ?? '-') ?></p>
            </div>
        </div>
    </div>
</div>
