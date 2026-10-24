<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Presensi Siswa PKL - <?= date('d F Y', strtotime($tanggal)) ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            color: #111827;
            background: #fff;
            margin: 0;
            padding: 24px;
            font-size: 11px;
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 3px double #111827;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .school-info {
            flex-grow: 1;
            text-align: center;
        }
        .school-name {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
        }
        .school-sub {
            font-size: 10px;
            color: #4b5563;
            margin: 0;
        }
        .doc-title {
            text-align: center;
            margin: 12px 0 20px 0;
        }
        .doc-title h2 {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            text-decoration: underline;
        }
        .doc-title p {
            margin: 0;
            font-size: 11px;
            color: #374151;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        th, td {
            border: 1px solid #9ca3af;
            padding: 6px 8px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #f3f4f6;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            text-align: center;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 9px;
            text-transform: uppercase;
        }
        .badge-hadir { background: #dcfce7; color: #166534; }
        .badge-izin { background: #fef3c7; color: #92400e; }
        .badge-sakit { background: #e0f2fe; color: #075985; }
        .badge-belum { background: #fee2e2; color: #991b1b; }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 36px;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 240px;
        }
        .signature-space {
            height: 60px;
        }
        @media print {
            body { padding: 10mm; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 16px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 16px; background: #2563eb; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Cetak Dokumen
        </button>
    </div>

    <div class="header">
        <div class="school-info">
            <h1 class="school-name"><?= esc($sekolah->nama_sekolah ?? 'SMK NEGERI 1 CILIMUS') ?></h1>
            <p class="school-sub"><?= esc($sekolah->alamat ?? 'Kabupaten Kuningan, Jawa Barat') ?> &bull; NPSN: <?= esc($sekolah->npsn ?? '20279827') ?></p>
            <p class="school-sub">Program Praktik Kerja Lapangan (PKL) Tahun Ajaran <?= esc($tahunAktif->tahun ?? date('Y')) ?></p>
        </div>
    </div>

    <div class="doc-title">
        <h2>REKAPITULASI PRESENSI HARIAN SISWA PKL</h2>
        <p>Tanggal: <strong><?= date('d F Y', strtotime($tanggal)) ?></strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">NO</th>
                <th>NAMA SISWA</th>
                <th>NISN</th>
                <th>KELAS</th>
                <th>JURUSAN</th>
                <th>MITRA DUDI</th>
                <th>PEMBIMBING</th>
                <th style="width: 60px;">MASUK</th>
                <th style="width: 60px;">PULANG</th>
                <th style="width: 65px;">STATUS</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($records)): ?>
                <tr>
                    <td colspan="10" class="text-center" style="padding: 20px;">Tidak ada data siswa untuk kriteria ini.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($records as $r): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="font-bold"><?= esc($r->nama) ?></td>
                        <td class="text-center"><?= esc($r->nisn ?: '-') ?></td>
                        <td class="text-center"><?= esc($r->nama_kelas ?: '-') ?></td>
                        <td><?= esc($r->nama_jurusan ?: '-') ?></td>
                        <td><?= esc($r->nama_dudi ?: 'Belum Ditempatkan') ?></td>
                        <td><?= esc($r->nama_pembimbing ?: '-') ?></td>
                        <td class="text-center"><?= esc($r->jam_masuk ?: '-') ?></td>
                        <td class="text-center"><?= esc($r->jam_pulang ?: '-') ?></td>
                        <td class="text-center">
                            <?php if ($r->status_absen === 'hadir'): ?>
                                <span class="badge badge-hadir">Hadir</span>
                            <?php elseif ($r->status_absen === 'izin'): ?>
                                <span class="badge badge-izin">Izin</span>
                            <?php elseif ($r->status_absen === 'sakit'): ?>
                                <span class="badge badge-sakit">Sakit</span>
                            <?php else: ?>
                                <span class="badge badge-belum">Belum</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>Mengetahui,<br>Kepala Sekolah</p>
            <div class="signature-space"></div>
            <p class="font-bold"><?= esc($sekolah->kepala_sekolah ?? '....................................') ?></p>
            <p>NIP. <?= esc($sekolah->nip_kepala_sekolah ?? '....................................') ?></p>
        </div>

        <div class="signature-box">
            <p><?= esc($sekolah->kabupaten ?? 'Kuningan') ?>, <?= date('d F Y') ?><br>Ketua Pokja PKL / Hubin</p>
            <div class="signature-space"></div>
            <p class="font-bold">........................................</p>
            <p>NIP. ....................................</p>
        </div>
    </div>

</body>
</html>
