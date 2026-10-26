<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Aplikasi Perhitungan JJM PKL dan Sinkronisasi Dapodik SMK">
    <meta name="author" content="PEKAEL IT Solution">
    <title><?= esc($title ?? 'PEKAEL - Sistem JJM PKL Dapodik') ?></title>

    <!-- Google Fonts Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome Free Icons -->
    <link href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">

    <!-- SB Admin 2 Stylesheet -->
    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="<?= base_url('assets/sbadmin2/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

    <!-- Modern Custom Stylesheet -->
    <link href="<?= base_url('assets/css/modern-custom.css') ?>?v=<?= file_exists(FCPATH . 'assets/css/modern-custom.css') ? filemtime(FCPATH . 'assets/css/modern-custom.css') : '1.1' ?>" rel="stylesheet">

    <?php if (($tema ?? 'light') === 'dark'): ?>
    <link href="<?= base_url('assets/css/theme-dark.css') ?>?v=<?= file_exists(FCPATH . 'assets/css/theme-dark.css') ? filemtime(FCPATH . 'assets/css/theme-dark.css') : '1.1' ?>" rel="stylesheet">
    <?php endif; ?>
</head>
<body id="page-top" class="<?= (($tema ?? 'light') === 'dark') ? 'theme-dark' : '' ?>">
<div id="wrapper">
