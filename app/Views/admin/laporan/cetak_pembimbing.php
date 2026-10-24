<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            color: #111827;
            background: #fff;
            margin: 0;
            padding: 24px;
            font-size: 12px;
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 3px double #111827;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            margin-right: 20px;
        }
        .school-info {
            flex-grow: 1;
            text-align: center;
        }
        .school-name {
            font-size: 18px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
        }
        .school-sub {
            font-size: 11px;
            color: #4b5563;
            margin: 0;
        }
        .doc-title {
            text-align: center;
            margin: 16px 0 24px 0;
        }
        .doc-title h2 {
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            text-decoration: underline;
        }
        .doc-title p {
            margin: 0;
            font-size: 12px;
            color: #374151;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #9ca3af;
            padding: 6px 8px;
            vertical-align: middle;
        }
        th {
            background-color: #f3f4f6;
            font-weight: 700;
            text-align: center;
        }
        .guru-section {
            background-color: #e5e7eb;
            font-weight: 700;
            font-size: 12px;
        }
        .signature-block {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .sign-box {
            width: 250px;
            text-align: center;
        }
        .sign-space {
            height: 65px;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            @page { margin: 1.5cm; }
        }
    </style>
</head>
<body>

<div class="no-print" style="margin-bottom: 20px; text-align: right;">
    <button onclick="window.print()" style="padding: 8px 16px; background: #2563eb; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
        Cetak Laporan / Simpan PDF
    </button>
</div>

<div class="header">
    <?php if (!empty($sekolah->logo) && file_exists(ROOTPATH . 'uploads/logo/' . $sekolah->logo)): ?>
        <img src="<?= base_url('uploads/logo/' . $sekolah->logo) ?>" class="logo" alt="Logo">
    <?php else: ?>
        <div style="width: 75px; height: 75px; background: #e5e7eb; border-radius: 8px; margin-right: 20px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #6b7280;">SMK</div>
    <?php endif; ?>
    <div class="school-info">
        <h1 class="school-name"><?= esc($sekolah->nama_sekolah ?? 'SMK NEGERI') ?></h1>
        <p class="school-sub">NPSN: <?= esc($sekolah->npsn ?? '-') ?> | <?= esc($sekolah->alamat ?? '-') ?></p>
    </div>
</div>

<div class="doc-title">
    <h2>Laporan Pembagian Pembimbing Praktik Kerja Lapangan (PKL)</h2>
    <p>Tahun Pelajaran: <strong><?= esc($tahun->tahun ?? '-') ?></strong> (Metode Koefisien Jam Dapodik)</p>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 35px;">No</th>
            <th>Kelas / Rombel</th>
            <th style="width: 110px;">NISN</th>
            <th>Nama Lengkap Siswa</th>
            <th>Mitra Dunia Kerja / DUDI</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($laporan)): ?>
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px; color: #6b7280;">Belum ada data distribusi pembimbing.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($laporan as $lp): ?>
                <tr class="guru-section">
                    <td colspan="5">
                        Pembimbing: <?= esc($lp['guru']->nama_guru) ?> (NIP: <?= esc($lp['guru']->nip ?: '-') ?>) &bull; Beban: <?= $lp['total_jam'] ?> JP &bull; Kuota: <?= $lp['total_siswa'] ?> Siswa
                    </td>
                </tr>
                <?php $no = 1; ?>
                <?php foreach ($lp['kelas'] as $k): ?>
                    <?php if (empty($k['daftar_siswa'])): ?>
                        <tr>
                            <td style="text-align: center;"><?= $no++ ?></td>
                            <td><?= esc($k['nama_kelas']) ?></td>
                            <td colspan="3" style="color: #6b7280; font-style: italic;">Belum ada siswa terpilih (<?= $k['jam'] ?> JP)</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($k['daftar_siswa'] as $s): ?>
                            <tr>
                                <td style="text-align: center;"><?= $no++ ?></td>
                                <td><?= esc($k['nama_kelas']) ?></td>
                                <td style="text-align: center; font-family: monospace;"><?= esc($s->nisn) ?></td>
                                <td><strong><?= esc($s->nama) ?></strong></td>
                                <td><?= esc($s->nama_dudi ?: '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div class="signature-block">
    <div class="sign-box">
        <p>Mengetahui,<br>Kepala Sekolah</p>
        <div class="sign-space"></div>
        <p><strong><?= esc($sekolah->kepala_sekolah ?? '........................') ?></strong><br>
        NIP. <?= esc($sekolah->nip_kepsek ?? '........................') ?></p>
    </div>
    <div class="sign-box">
        <p>Kota, <?= date('d F Y') ?><br>Koordinator Hubin / PKL</p>
        <div class="sign-space"></div>
        <p><strong>(.......................................)</strong><br>
        NIP. ...................................</p>
    </div>
</div>

</body>
</html>
