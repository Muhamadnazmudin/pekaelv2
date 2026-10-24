<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column" style="min-height: 100vh; width: 100%; overflow-x: hidden;">

    <!-- Main Content -->
    <div id="content" style="flex: 1 0 auto;">

        <!-- Topbar Navbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm px-3 px-md-4 d-flex justify-content-between align-items-center" style="height: 70px; border-bottom: 1px solid #e2e8f0;">

            <!-- Left Section: Toggle & School Brand -->
            <div class="d-flex align-items-center">
                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3 text-secondary p-2">
                    <i class="fa fa-bars fa-lg"></i>
                </button>

                <!-- School & Year Identity -->
                <div class="d-none d-md-flex align-items-center">
                    <div class="mr-3">
                        <span class="text-xs text-uppercase font-weight-bold text-muted d-block" style="letter-spacing: 0.05em; line-height: 1;">Satuan Pendidikan</span>
                        <span class="font-weight-bold text-gray-900" style="font-size: 0.95rem;">
                            <i class="fas fa-school text-primary mr-1"></i>
                            <?= esc($sekolah->nama_sekolah ?? 'SMK Negeri') ?>
                        </span>
                        <span class="badge badge-light border text-muted ml-1 font-weight-normal text-xs">NPSN: <?= esc($sekolah->npsn ?? '-') ?></span>
                    </div>

                    <div class="border-left pl-3 ml-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted d-block" style="letter-spacing: 0.05em; line-height: 1;">Tahun Pelajaran</span>
                        <span class="badge badge-primary font-weight-bold px-2 py-1" style="font-size: 0.8rem;">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            <?= esc($tahun_aktif->tahun ?? 'Belum Ditentukan') ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Section: Dapodik Status & User Dropdown -->
            <ul class="navbar-nav align-items-center flex-row">

                <!-- Dapodik Quick Status Pill -->
                <li class="nav-item mr-2 mr-lg-3 d-none d-sm-block">
                    <a href="<?= site_url('dapodik') ?>" class="dapo-live-badge connected text-decoration-none shadow-sm">
                        <span class="dapo-pulse online"></span>
                        <span style="white-space: nowrap;">Dapodik: Terhubung (Read-Only)</span>
                    </a>
                </li>

                <!-- Cek Mandiri Siswa Link -->
                <!-- <li class="nav-item mr-2 mr-lg-3">
                    <a class="btn btn-sm btn-outline-primary shadow-sm font-weight-bold" href="<?= site_url('cek') ?>" target="_blank" title="Buka Halaman Cek Mandiri Siswa">
                        <i class="fas fa-external-link-alt mr-1"></i> <span class="d-none d-md-inline">Portal</span> Siswa
                    </a>
                </li> -->

                <!-- Metode Perhitungan PKL Dropdown Switcher -->
                <li class="nav-item dropdown mr-2 mr-lg-3">
                    <a class="nav-link dropdown-toggle p-0 d-flex align-items-center" href="#" id="metodeDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php if (($metode_perhitungan ?? 'koefisien') === 'proporsional'): ?>
                            <span class="badge badge-info shadow-sm px-2.5 py-1.5 font-weight-bold d-flex align-items-center" style="font-size: 0.78rem;">
                                <i class="fas fa-layer-group mr-1.5"></i>
                                <span class="d-none d-md-inline mr-1">Metode:</span> Proporsional Kelas
                                <i class="fas fa-chevron-down ml-1.5 text-xs opacity-75"></i>
                            </span>
                        <?php else: ?>
                            <span class="badge badge-primary shadow-sm px-2.5 py-1.5 font-weight-bold d-flex align-items-center" style="font-size: 0.78rem;">
                                <i class="fas fa-percentage mr-1.5"></i>
                                <span class="d-none d-md-inline mr-1">Metode:</span> Koefisien Konversi
                                <i class="fas fa-chevron-down ml-1.5 text-xs opacity-75"></i>
                            </span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow-lg animated--grow-in border-0 rounded-lg p-2" aria-labelledby="metodeDropdown" style="width: 290px;">
                        <div class="dropdown-header font-weight-bold text-uppercase text-xs text-primary px-2 py-1">
                            Pilih Metode Perhitungan PKL
                        </div>
                        <a class="dropdown-item rounded-lg py-2 mb-1 <?= (($metode_perhitungan ?? 'koefisien') === 'koefisien') ? 'bg-primary-light font-weight-bold text-primary' : '' ?>" href="<?= site_url('pengaturan/set_metode?metode=koefisien') ?>">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-percentage mt-1 mr-2 text-primary"></i>
                                <div>
                                    <div class="font-weight-bold">1. Koefisien Konversi</div>
                                    <div class="text-xs text-muted" style="white-space: normal;">Bagi rata dengan rumus (Rombel * 46)/Siswa.</div>
                                </div>
                            </div>
                        </a>
                        <a class="dropdown-item rounded-lg py-2 <?= (($metode_perhitungan ?? 'koefisien') === 'proporsional') ? 'bg-info-light font-weight-bold text-info' : '' ?>" href="<?= site_url('pengaturan/set_metode?metode=proporsional') ?>">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-layer-group mt-1 mr-2 text-info"></i>
                                <div>
                                    <div class="font-weight-bold">2. Proporsional Kelas</div>
                                    <div class="text-xs text-muted" style="white-space: normal;">Bagi kuota pembimbing murni per kelas rombel.</div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider my-1"></div>
                        <a class="dropdown-item text-xs text-center font-weight-bold text-muted py-1" href="<?= site_url('pengaturan') ?>">
                            <i class="fas fa-cog mr-1"></i> Buka Pengaturan Lengkap
                        </a>
                    </div>
                </li>

                <!-- Dark / Light Mode Toggle Button -->
                <li class="nav-item mr-2 mr-lg-3">
                    <button type="button" class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center shadow-sm" id="btnToggleTheme" style="width: 36px; height: 36px;" title="Ganti Tema (Dark / Light Mode)">
                        <?php if (($tema ?? 'light') === 'dark'): ?>
                            <i class="fas fa-sun text-warning"></i>
                        <?php else: ?>
                            <i class="fas fa-moon text-gray-700"></i>
                        <?php endif; ?>
                    </button>
                </li>

                <div class="topbar-divider d-none d-sm-block mr-3"></div>

                <!-- User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle p-0 d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="text-right mr-2 d-none d-lg-inline text-truncate" style="max-width: 150px;">
                            <span class="font-weight-bold text-gray-900 d-block" style="font-size: 0.88rem; line-height: 1.1;">
                                <?= esc(session()->get('nama_lengkap') ?: session()->get('username') ?: 'Operator') ?>
                            </span>
                            <span class="badge badge-light border text-muted text-uppercase text-xs font-weight-bold px-1">
                                <?= esc(session()->get('role') ?: 'admin') ?>
                            </span>
                        </div>
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center font-weight-bold shadow-sm" style="width: 40px; height: 40px; font-size: 1rem; border: 2px solid #e0e7ff;">
                            <?= strtoupper(substr(session()->get('username') ?: 'A', 0, 1)) ?>
                        </div>
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow-lg animated--grow-in border-0 rounded-lg mt-2" aria-labelledby="userDropdown">
                        <div class="dropdown-header font-weight-bold text-uppercase text-xs text-primary">Akun Operator</div>
                        <a class="dropdown-item py-2" href="<?= site_url('pengaturan') ?>">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Pengaturan Sistem
                        </a>
                        <a class="dropdown-item py-2" href="<?= site_url('users') ?>">
                            <i class="fas fa-user-shield fa-sm fa-fw mr-2 text-gray-400"></i>
                            Manajemen Operator
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item py-2 text-danger font-weight-bold" href="<?= site_url('auth/logout') ?>">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                            Keluar (Logout)
                        </a>
                    </div>
                </li>

            </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content Container -->
        <div class="container-fluid px-3 px-md-4 pb-5">

            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center rounded-lg mb-4" role="alert" style="background-color: #ecfdf5; color: #065f46; border-left: 5px solid #10b981 !important;">
                <i class="fas fa-check-circle fa-lg mr-3 text-success"></i>
                <div class="flex-grow-1 font-weight-500">
                    <?= session()->getFlashdata('success') ?>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center rounded-lg mb-4" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 5px solid #ef4444 !important;">
                <i class="fas fa-exclamation-triangle fa-lg mr-3 text-danger"></i>
                <div class="flex-grow-1 font-weight-500">
                    <?= session()->getFlashdata('error') ?>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php endif; ?>
