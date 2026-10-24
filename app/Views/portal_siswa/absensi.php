<?= $this->extend('portal_siswa/layout') ?>

<?= $this->section('content') ?>

<div class="row">

    <!-- Left Column: Camera, GPS & Check-In Form -->
    <div class="col-lg-7 mb-4">
        <div class="card border-0 shadow-sm rounded-2xl overflow-hidden">
            
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="font-weight-bold text-gray-900 mb-0">
                        <i class="fas fa-camera text-primary mr-2"></i> Presensi Selfie &amp; Geolocation
                    </h5>
                    <span class="text-xs text-muted"><?= date('l, d F Y') ?></span>
                </div>
                <!-- Live Clock -->
                <div class="text-right">
                    <span id="digitalClock" class="font-weight-bold text-primary px-3 py-1 bg-light border rounded-pill text-sm">
                        --:--:-- WIB
                    </span>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- DUDI Geolocation Target Card -->
                <div class="p-3 bg-light rounded-xl border mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="text-xs font-weight-bold text-uppercase text-muted" style="letter-spacing: 0.05em;">
                            <i class="fas fa-building text-primary mr-1"></i> Tempat PKL: <?= esc($siswa->nama_dudi ?? 'Belum Ditempatkan') ?>
                        </span>
                        <?php if (!empty($siswa->dudi_lat) && !empty($siswa->dudi_long)): ?>
                            <span class="badge badge-success px-2 py-0.5 text-xs font-weight-normal">
                                Radius: <?= esc($siswa->radius_meter ?? 100) ?> Meter
                            </span>
                        <?php else: ?>
                            <span class="badge badge-warning px-2 py-0.5 text-xs font-weight-normal">
                                Titik Belum Diset
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="text-xs text-secondary">
                        <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                        <?= esc($siswa->alamat_dudi ?: 'Alamat belum dilengkapi.') ?>
                    </div>

                    <!-- GPS Live Status -->
                    <div id="gpsStatusBox" class="mt-2.5 p-2.5 rounded-lg border text-xs font-weight-600 d-flex align-items-center bg-white">
                        <div class="spinner-border spinner-border-sm text-primary mr-2" role="status" id="gpsSpinner"></div>
                        <span id="gpsStatusText">Mendeteksi koordinat GPS lokasi Anda...</span>
                    </div>
                </div>

                <!-- Camera / Selfie Viewport -->
                <div class="text-center mb-3">
                    <div class="position-relative d-inline-block rounded-2xl overflow-hidden border shadow-sm bg-dark" style="width: 100%; max-width: 420px; aspect-ratio: 4/3;">
                        <video id="webcamVideo" autoplay playsinline class="w-100 h-100" style="object-fit: cover; transform: scaleX(-1);"></video>
                        <canvas id="photoCanvas" class="w-100 h-100 position-absolute" style="top: 0; left: 0; object-fit: cover; display: none;"></canvas>
                        
                        <!-- Camera Controls Overlay -->
                        <div class="position-absolute d-flex justify-content-center align-items-center" style="bottom: 12px; left: 0; right: 0; z-index: 10;">
                            <button type="button" id="btnSnap" class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; border: 3px solid #fff;">
                                <i class="fas fa-camera fa-lg"></i>
                            </button>
                            <button type="button" id="btnRetake" class="btn btn-warning rounded-circle shadow-lg d-flex align-items-center justify-content-center ml-2" style="width: 44px; height: 44px; border: 2px solid #fff; display: none;">
                                <i class="fas fa-redo"></i>
                            </button>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted text-xs">
                            <i class="fas fa-shield-alt mr-1 text-primary"></i> Ambil foto selfie wajah Anda dengan jelas di lokasi tempat PKL.
                        </small>
                    </div>
                </div>

                <!-- Form Presensi: Masuk vs Pulang -->
                <?php 
                    $isMasukDone = (!empty($absenHariIni) && !empty($absenHariIni->jam_masuk));
                    $isPulangDone = (!empty($absenHariIni) && !empty($absenHariIni->jam_pulang));
                    $actionUrl = !$isMasukDone ? site_url('siswa/absen_masuk') : site_url('siswa/absen_pulang');
                ?>

                <?php if ($isMasukDone && $isPulangDone): ?>
                    <div class="alert alert-success border-0 shadow-sm rounded-xl p-3 text-center mb-0" style="background-color: #ecfdf5; color: #065f46;">
                        <i class="fas fa-check-double fa-2x mb-2 text-success"></i>
                        <h6 class="font-weight-bold mb-1">Presensi Hari Ini Telah Lengkap</h6>
                        <p class="text-xs mb-0">Absen Masuk: <strong><?= esc($absenHariIni->jam_masuk) ?> WIB</strong> &bull; Absen Pulang: <strong><?= esc($absenHariIni->jam_pulang) ?> WIB</strong></p>
                    </div>
                <?php else: ?>
                    <form action="<?= $actionUrl ?>" method="POST" id="formPresensi" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <!-- Hidden Geolocation & Base64 Inputs -->
                        <input type="hidden" name="latitude" id="inputLatitude">
                        <input type="hidden" name="longitude" id="inputLongitude">
                        <input type="hidden" name="foto_base64" id="inputFotoBase64">

                        <!-- Fallback upload photo for mobile / if webcam blocked -->
                        <div class="form-group mb-3 text-left">
                            <label class="text-xs font-weight-bold text-muted mb-1">Atau Pilih / Foto dari Kamera HP:</label>
                            <input type="file" name="foto_file" id="inputFile" class="form-control-file text-xs" accept="image/*" capture="user">
                        </div>

                        <?php if (!$isMasukDone): ?>
                            <button type="submit" id="btnSubmitPresensi" class="btn btn-primary btn-block btn-lg font-weight-bold shadow-sm py-3 rounded-xl" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none;">
                                <i class="fas fa-sign-in-alt mr-2"></i> Kirim Absen Masuk Sekarang
                            </button>
                        <?php else: ?>
                            <div class="p-2.5 rounded-lg bg-light border text-xs text-muted mb-3 text-left">
                                <i class="fas fa-check-circle text-success mr-1"></i> Anda telah absen masuk pada pukul <strong><?= esc($absenHariIni->jam_masuk) ?> WIB</strong>.
                            </div>
                            <button type="submit" id="btnSubmitPresensi" class="btn btn-warning btn-block btn-lg font-weight-bold shadow-sm py-3 rounded-xl text-white" style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%); border: none;">
                                <i class="fas fa-sign-out-alt mr-2"></i> Kirim Absen Pulang Sekarang
                            </button>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- Right Column: Today's Status & Attendance History -->
    <div class="col-lg-5">
        
        <!-- Status Today Card -->
        <div class="card border-0 shadow-sm rounded-xl p-3 mb-3">
            <h6 class="font-weight-bold text-gray-900 text-sm mb-2">
                <i class="fas fa-calendar-check text-primary mr-1"></i> Rekap Hari Ini
            </h6>
            <div class="row g-2 text-center">
                <div class="col-6">
                    <div class="p-2.5 bg-light rounded-lg border">
                        <span class="text-xs text-muted d-block font-weight-bold">JAM MASUK</span>
                        <strong class="text-primary" style="font-size: 1.1rem;">
                            <?= !empty($absenHariIni->jam_masuk) ? esc($absenHariIni->jam_masuk) : '--:--' ?>
                        </strong>
                        <span class="text-xs text-muted d-block">WIB</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2.5 bg-light rounded-lg border">
                        <span class="text-xs text-muted d-block font-weight-bold">JAM PULANG</span>
                        <strong class="text-warning" style="font-size: 1.1rem;">
                            <?= !empty($absenHariIni->jam_pulang) ? esc($absenHariIni->jam_pulang) : '--:--' ?>
                        </strong>
                        <span class="text-xs text-muted d-block">WIB</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 30-Day Attendance History Table -->
        <div class="card border-0 shadow-sm rounded-xl overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-gray-900 text-sm">
                    <i class="fas fa-history text-primary mr-1"></i> Riwayat Presensi (30 Hari Terakhir)
                </h6>
                <span class="badge badge-light border text-xs"><?= count($riwayatAbsensi) ?> Data</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($riwayatAbsensi)): ?>
                    <div class="text-center py-4 text-muted text-xs">
                        Belum ada riwayat absensi tercatat.
                    </div>
                <?php else: ?>
                    <div class="table-responsive" style="max-height: 480px;">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.82rem;">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Selfie</th>
                                    <th>Masuk</th>
                                    <th>Pulang</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayatAbsensi as $r): ?>
                                    <tr>
                                        <td class="font-weight-bold text-gray-800">
                                            <?= date('d/m/y', strtotime($r->tanggal)) ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($r->foto_masuk)): ?>
                                                <a href="<?= base_url('uploads/absensi/' . $r->foto_masuk) ?>" target="_blank">
                                                    <img src="<?= base_url('uploads/absensi/' . $r->foto_masuk) ?>" class="rounded shadow-sm" style="width: 32px; height: 32px; object-fit: cover;">
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted text-xs">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-primary"><?= esc($r->jam_masuk ?: '-') ?></span>
                                            <?php if ($r->jarak_masuk !== null): ?>
                                                <div class="text-xs text-muted"><?= esc($r->jarak_masuk) ?>m</div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-warning"><?= esc($r->jam_pulang ?: '-') ?></span>
                                        </td>
                                        <td>
                                            <?php if ($r->status === 'hadir'): ?>
                                                <span class="badge badge-soft-success px-2 py-0.5">Hadir</span>
                                            <?php elseif ($r->status === 'izin'): ?>
                                                <span class="badge badge-soft-info px-2 py-0.5">Izin</span>
                                            <?php else: ?>
                                                <span class="badge badge-soft-danger px-2 py-0.5">Alpha</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // 1. Digital Clock
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        $('#digitalClock').text(`${h}:${m}:${s} WIB`);
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 2. Target DUDI Coordinates
    const targetLat = <?= !empty($siswa->dudi_lat) ? (float)$siswa->dudi_lat : 'null' ?>;
    const targetLong = <?= !empty($siswa->dudi_long) ? (float)$siswa->dudi_long : 'null' ?>;
    const maxRadius = <?= (int)($siswa->radius_meter ?: 100) ?>;

    // Haversine client-side distance formula
    function getDistanceMeters(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return Math.round(R * c);
    }

    // 3. Geolocation API Tracker
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            $('#inputLatitude').val(lat);
            $('#inputLongitude').val(lng);
            $('#gpsSpinner').hide();

            if (targetLat !== null && targetLong !== null) {
                const dist = getDistanceMeters(targetLat, targetLong, lat, lng);
                const allowedRadiusWithTolerance = maxRadius + 50;

                if (dist <= allowedRadiusWithTolerance) {
                    $('#gpsStatusBox')
                        .removeClass('bg-white border text-danger text-warning')
                        .addClass('bg-success-light text-success border-success')
                        .html(`<i class="fas fa-check-circle fa-lg mr-2 text-success"></i>
                               <div>Lokasi Valid: Jarak Anda <strong>${dist} meter</strong> dari titik DUDI (Maks: ${maxRadius}m).</div>`);
                } else {
                    $('#gpsStatusBox')
                        .removeClass('bg-white border text-success text-warning')
                        .addClass('bg-danger-light text-danger border-danger')
                        .html(`<i class="fas fa-exclamation-triangle fa-lg mr-2 text-danger"></i>
                               <div>Di Luar Radius: Jarak Anda <strong>${dist} meter</strong> dari DUDI (Batas: ${maxRadius}m). Dekati tempat PKL Anda.</div>`);
                }
            } else {
                $('#gpsStatusBox')
                    .removeClass('bg-white border text-danger text-success')
                    .addClass('bg-warning-light text-warning-dark border-warning')
                    .html(`<i class="fas fa-map-marker-alt fa-lg mr-2 text-warning"></i>
                           <div>Koordinat Anda: ${lat.toFixed(5)}, ${lng.toFixed(5)} (Koordinat DUDI belum disetel, presensi diizinkan).</div>`);
            }
        }, function(err) {
            $('#gpsSpinner').hide();
            $('#gpsStatusBox')
                .addClass('bg-warning-light text-warning-dark border-warning')
                .html(`<i class="fas fa-exclamation-circle fa-lg mr-2 text-warning"></i>
                       <div>Gagal membaca GPS: ${err.message}. Pastikan izin lokasi (GPS) diaktifkan di browser/HP Anda.</div>`);
        }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
    } else {
        $('#gpsSpinner').hide();
        $('#gpsStatusText').text('Browser Anda tidak mendukung Geolocation.');
    }

    // 4. Webcam / Selfie Capture via Canvas
    const video = document.getElementById('webcamVideo');
    const canvas = document.getElementById('photoCanvas');
    let streamObj = null;

    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } } 
        }).then(function(stream) {
            streamObj = stream;
            video.srcObject = stream;
        }).catch(function(err) {
            console.warn('Camera not available or permission denied:', err);
            $('#webcamVideo').hide();
            $('#btnSnap').hide();
        });
    }

    // Snap Selfie Photo
    $('#btnSnap').on('click', function() {
        if (!video.videoWidth) {
            alert('Kamera belum siap atau izin kamera belum diizinkan. Silakan gunakan upload berkas foto.');
            return;
        }
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
        $('#inputFotoBase64').val(dataUrl);

        $(canvas).show();
        $(video).hide();
        $('#btnSnap').hide();
        $('#btnRetake').show();
    });

    // Retake Photo
    $('#btnRetake').on('click', function() {
        $('#inputFotoBase64').val('');
        $(canvas).hide();
        $(video).show();
        $('#btnRetake').hide();
        $('#btnSnap').show();
    });

    // Form Submit Validation
    $('#formPresensi').on('submit', function(e) {
        const fotoBase64 = $('#inputFotoBase64').val();
        const fileUpload = $('#inputFile').val();

        if (!fotoBase64 && !fileUpload) {
            e.preventDefault();
            alert('Silakan ambil foto selfie wajah Anda terlebih dahulu dengan menekan tombol kamera atau memilih foto.');
            return false;
        }
    });
});
</script>
<?= $this->endSection() ?>
