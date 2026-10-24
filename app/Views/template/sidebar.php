<?php
$uri = service('uri');
$seg1 = $uri->getSegment(1);
$seg2 = $uri->getTotalSegments() >= 2 ? $uri->getSegment(2) : '';

$isMaster     = in_array($seg1, ['sekolah', 'tahun', 'jurusan', 'kelas', 'guru', 'siswa', 'dudi']);
$isPkl        = in_array($seg1, ['mappingdudi', 'koefisien', 'pembagianjam', 'pembimbing', 'pembimbingkelas']);
$isDist       = in_array($seg1, ['distribusimanual', 'distribusi_proporsional']);
$isMonitoring = in_array($seg1, ['monitoring', 'monitoring_absensi', 'monitoring_jurnal']);
$isLapor      = in_array($seg1, ['laporan-pembimbing', 'laporan_propor', 'rekappkl']) || ($seg1 === 'laporan');
$isSistem     = in_array($seg1, ['pengaturan', 'backup', 'users', 'pusatunduhan']);
?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- BRAND -->
    <a class="sidebar-brand d-flex align-items-center justify-content-start px-3" href="<?= site_url('dashboard') ?>">
        <div class="sidebar-brand-icon brand-logo-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="sidebar-brand-text mx-2 text-left">
            <span class="font-weight-bold" style="letter-spacing: 0.05em; font-size: 1.1rem;">PEKAEL</span>
            <div style="font-size: 0.65rem; opacity: 0.75; font-weight: 500; text-transform: uppercase;">JJM PKL & Dapodik</div>
        </div>
    </a>

    <hr class="sidebar-divider my-2" style="border-color: rgba(255,255,255,0.08);">

    <!-- DASHBOARD -->
    <li class="nav-item <?= ($seg1 === 'dashboard' || empty($seg1)) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('dashboard') ?>">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- INTEGRASI DAPODIK (HIGHLIGHT) -->
    <li class="nav-item <?= ($seg1 === 'dapodik') ? 'active' : '' ?>">
        <a class="nav-link d-flex align-items-center justify-content-between" href="<?= site_url('dapodik') ?>" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3);">
            <div>
                <i class="fas fa-sync-alt text-success"></i>
                <span class="font-weight-bold text-white">Tarik Dapodik</span>
            </div>
            <span class="badge badge-success px-2 py-1" style="font-size: 0.65rem;">READ-ONLY</span>
        </a>
    </li>

    <hr class="sidebar-divider" style="border-color: rgba(255,255,255,0.08);">

    <!-- MASTER DATA -->
    <div class="sidebar-heading">
        Data Master
    </div>

    <li class="nav-item <?= $isMaster ? 'active' : '' ?>">
        <a class="nav-link <?= $isMaster ? '' : 'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseMaster" aria-expanded="<?= $isMaster ? 'true' : 'false' ?>">
            <i class="fas fa-database"></i>
            <span>Master Sekolah</span>
        </a>
        <div id="collapseMaster" class="collapse <?= $isMaster ? 'show' : '' ?>" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm">
                <a class="collapse-item <?= ($seg1 === 'sekolah') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('sekolah') ?>">
                    <i class="fas fa-school mr-2 text-muted"></i> Profil Sekolah
                </a>
                <a class="collapse-item <?= ($seg1 === 'tahun') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('tahun') ?>">
                    <i class="fas fa-calendar-alt mr-2 text-muted"></i> Tahun Ajaran
                </a>
                <a class="collapse-item <?= ($seg1 === 'jurusan') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('jurusan') ?>">
                    <i class="fas fa-laptop-code mr-2 text-muted"></i> Jurusan / Keahlian
                </a>
                <a class="collapse-item <?= ($seg1 === 'kelas') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('kelas') ?>">
                    <i class="fas fa-chalkboard mr-2 text-muted"></i> Kelas & Rombel
                </a>
                <a class="collapse-item <?= ($seg1 === 'guru') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('guru') ?>">
                    <i class="fas fa-chalkboard-teacher mr-2 text-muted"></i> Guru (PTK)
                </a>
                <a class="collapse-item <?= ($seg1 === 'siswa') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('siswa') ?>">
                    <i class="fas fa-user-graduate mr-2 text-muted"></i> Siswa PKL
                </a>
                <a class="collapse-item <?= ($seg1 === 'dudi') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('dudi') ?>">
                    <i class="fas fa-building mr-2 text-muted"></i> Mitra DUDI & MoU
                </a>
            </div>
        </div>
    </li>

    <!-- PROSES & PERHITUNGAN JJM PKL -->
    <div class="sidebar-heading d-flex align-items-center justify-content-between">
        <span>Perhitungan JJM</span>
        <span class="badge <?= (($metode_perhitungan ?? 'koefisien') === 'proporsional') ? 'badge-info' : 'badge-primary' ?> px-1.5 py-0.5 text-uppercase" style="font-size: 0.62rem;">
            <?= (($metode_perhitungan ?? 'koefisien') === 'proporsional') ? 'Proporsional' : 'Koefisien' ?>
        </span>
    </div>

    <li class="nav-item <?= $isPkl ? 'active' : '' ?>">
        <a class="nav-link <?= $isPkl ? '' : 'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapsePkl" aria-expanded="<?= $isPkl ? 'true' : 'false' ?>">
            <i class="fas fa-calculator"></i>
            <span>Proses & Kuota JJM</span>
        </a>
        <div id="collapsePkl" class="collapse <?= $isPkl ? 'show' : '' ?>" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm">
                <a class="collapse-item <?= ($seg1 === 'mappingdudi') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('mappingdudi') ?>">
                    <i class="fas fa-map-marked-alt mr-2 text-muted"></i> 1. Mapping DUDI
                </a>
                <?php if (($metode_perhitungan ?? 'koefisien') === 'koefisien'): ?>
                    <a class="collapse-item <?= ($seg1 === 'koefisien') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('koefisien') ?>">
                        <i class="fas fa-percentage mr-2 text-muted"></i> 2. Koefisien PKL
                    </a>
                    <a class="collapse-item <?= ($seg1 === 'pembagianjam') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('pembagianjam') ?>">
                        <i class="fas fa-clock mr-2 text-muted"></i> 3. Pembagian Jam (46 JP)
                    </a>
                    <a class="collapse-item <?= ($seg1 === 'pembimbing') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('pembimbing') ?>">
                        <i class="fas fa-user-tie mr-2 text-muted"></i> 4. Pembimbing (Koefisien)
                    </a>
                <?php else: ?>
                    <a class="collapse-item <?= ($seg1 === 'pembagianjam') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('pembagianjam') ?>">
                        <i class="fas fa-clock mr-2 text-muted"></i> 2. Pembagian Jam (46 JP)
                    </a>
                    <a class="collapse-item <?= ($seg1 === 'pembimbingkelas') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('pembimbingkelas') ?>">
                        <i class="fas fa-layer-group mr-2 text-muted"></i> 3. Pembimbing (Prop. Kelas)
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </li>

    <!-- DISTRIBUSI SISWA -->
    <li class="nav-item <?= $isDist ? 'active' : '' ?>">
        <a class="nav-link <?= $isDist ? '' : 'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseDist" aria-expanded="<?= $isDist ? 'true' : 'false' ?>">
            <i class="fas fa-users-cog"></i>
            <span>Distribusi Bimbingan</span>
        </a>
        <div id="collapseDist" class="collapse <?= $isDist ? 'show' : '' ?>" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm">
                <?php if (($metode_perhitungan ?? 'koefisien') === 'koefisien'): ?>
                    <a class="collapse-item <?= ($seg1 === 'distribusimanual') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('distribusimanual') ?>">
                        <i class="fas fa-user-check mr-2 text-muted"></i> Distribusi (Metode Koef)
                    </a>
                <?php else: ?>
                    <a class="collapse-item <?= ($seg1 === 'distribusi_proporsional') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('distribusi_proporsional') ?>">
                        <i class="fas fa-sliders-h mr-2 text-muted"></i> Distribusi (Metode Prop)
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </li>

    <!-- MONITORING AKTIVITAS PKL -->
    <div class="sidebar-heading">
        Monitoring Siswa
    </div>

    <li class="nav-item <?= $isMonitoring ? 'active' : '' ?>">
        <a class="nav-link <?= $isMonitoring ? '' : 'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseMonitoring" aria-expanded="<?= $isMonitoring ? 'true' : 'false' ?>">
            <i class="fas fa-desktop"></i>
            <span>Monitoring Aktivitas</span>
        </a>
        <div id="collapseMonitoring" class="collapse <?= $isMonitoring ? 'show' : '' ?>" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm">
                <a class="collapse-item <?= ($seg1 === 'monitoring' && $seg2 === 'absensi') || $seg1 === 'monitoring_absensi' ? 'active font-weight-bold' : '' ?>" href="<?= site_url('monitoring/absensi') ?>">
                    <i class="fas fa-camera mr-2 text-muted"></i> Presensi Siswa
                </a>
                <a class="collapse-item <?= ($seg1 === 'monitoring' && $seg2 === 'jurnal') || $seg1 === 'monitoring_jurnal' ? 'active font-weight-bold' : '' ?>" href="<?= site_url('monitoring/jurnal') ?>">
                    <i class="fas fa-book-reader mr-2 text-muted"></i> Jurnal Kegiatan
                </a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider" style="border-color: rgba(255,255,255,0.08);">

    <!-- LAPORAN & REKAP DAPODIK -->
    <div class="sidebar-heading">
        Pelaporan Dapodik
    </div>

    <li class="nav-item <?= $isLapor ? 'active' : '' ?>">
        <a class="nav-link <?= $isLapor ? '' : 'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseLaporan" aria-expanded="<?= $isLapor ? 'true' : 'false' ?>">
            <i class="fas fa-file-invoice"></i>
            <span>Laporan & Cetak</span>
        </a>
        <div id="collapseLaporan" class="collapse <?= $isLapor ? 'show' : '' ?>" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm">
                <?php if (($metode_perhitungan ?? 'koefisien') === 'koefisien'): ?>
                    <a class="collapse-item <?= ($seg1 === 'laporan-pembimbing' || $seg2 === 'pembimbing') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('laporan-pembimbing') ?>">
                        <i class="fas fa-print mr-2 text-muted"></i> Cetak Pembimbing (Koef)
                    </a>
                <?php else: ?>
                    <a class="collapse-item <?= ($seg1 === 'laporan_propor') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('laporan_propor') ?>">
                        <i class="fas fa-print mr-2 text-muted"></i> Cetak Pembimbing (Prop)
                    </a>
                <?php endif; ?>
                <a class="collapse-item <?= ($seg1 === 'rekappkl' || $seg2 === 'rekap') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('rekappkl') ?>">
                    <i class="fas fa-table mr-2 text-muted"></i> Rekap PKL (Siap Dapodik)
                </a>
            </div>
        </div>
    </li>

    <!-- PUSAT UNDUHAN -->
    <li class="nav-item <?= ($seg1 === 'pusatunduhan') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('pusatunduhan') ?>">
            <i class="fas fa-cloud-download-alt"></i>
            <span>Pusat Unduhan</span>
        </a>
    </li>

    <hr class="sidebar-divider" style="border-color: rgba(255,255,255,0.08);">

    <!-- SISTEM & PENGATURAN -->
    <div class="sidebar-heading">
        Sistem
    </div>

    <li class="nav-item <?= $isSistem ? 'active' : '' ?>">
        <a class="nav-link <?= $isSistem ? '' : 'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseSistem" aria-expanded="<?= $isSistem ? 'true' : 'false' ?>">
            <i class="fas fa-cogs"></i>
            <span>Pengaturan Sistem</span>
        </a>
        <div id="collapseSistem" class="collapse <?= $isSistem ? 'show' : '' ?>" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded-lg shadow-sm">
                <a class="collapse-item <?= ($seg1 === 'pengaturan') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('pengaturan') ?>">
                    <i class="fas fa-sliders-h mr-2 text-muted"></i> Pengaturan Aplikasi
                </a>
                <a class="collapse-item <?= ($seg1 === 'backup') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('backup') ?>">
                    <i class="fas fa-database mr-2 text-muted"></i> Backup Database SQL
                </a>
                <a class="collapse-item <?= ($seg1 === 'users') ? 'active font-weight-bold' : '' ?>" href="<?= site_url('users') ?>">
                    <i class="fas fa-users-cog mr-2 text-muted"></i> Manajemen Operator
                </a>
            </div>
        </div>
    </li>

    <!-- LOGOUT -->
    <li class="nav-item">
        <a class="nav-link text-danger font-weight-bold" href="<?= site_url('auth/logout') ?>">
            <i class="fas fa-sign-out-alt text-danger"></i>
            <span>Keluar (Logout)</span>
        </a>
    </li>

    <div class="text-center d-none d-md-inline mt-3">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
