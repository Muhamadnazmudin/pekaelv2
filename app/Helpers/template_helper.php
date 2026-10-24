<?php

use Config\Services;

if (!function_exists('template')) {
    /**
     * Render full layout template for school operator admin interface
     */
    function template(string $view, array $data = []): string
    {
        $db = \Config\Database::connect();

        if (!isset($data['tahun_aktif'])) {
            $data['tahun_aktif'] = $db->table('tahun_ajaran')->where('status', 'aktif')->get()->getRow();
        }
        if (!isset($data['sekolah'])) {
            $data['sekolah'] = $db->table('sekolah')->limit(1)->get()->getRow();
        }
        if (!isset($data['tema'])) {
            $row = $db->table('pengaturan')->where('nama_pengaturan', 'tema_aplikasi')->get()->getRow();
            $data['tema'] = $row ? $row->value : 'light';
        }
        if (!isset($data['metode_perhitungan'])) {
            $row = $db->table('pengaturan')->where('nama_pengaturan', 'metode_perhitungan')->get()->getRow();
            $data['metode_perhitungan'] = ($row && in_array($row->value, ['koefisien', 'proporsional'])) ? $row->value : 'koefisien';
        }
        if (!isset($data['title'])) {
            $data['title'] = 'PEKAEL - Sistem JJM PKL Dapodik';
        }

        echo view('template/header', $data);
        echo view('template/sidebar', $data);
        echo view('template/topbar', $data);
        echo view($view, $data);
        echo view('template/footer', $data);
        echo view('template/script', $data);

        return '';
    }
}

if (!function_exists('tahun_aktif')) {
    function tahun_aktif()
    {
        $db = \Config\Database::connect();
        return $db->table('tahun_ajaran')->where('status', 'aktif')->get()->getRow();
    }
}

if (!function_exists('jam_pkl_wajib')) {
    function jam_pkl_wajib(): int
    {
        $db = \Config\Database::connect();
        $row = $db->table('pengaturan_pkl')->limit(1)->get()->getRow();
        return $row && !empty($row->jam_pkl) ? (int)$row->jam_pkl : 46;
    }
}

if (!function_exists('koefisien_aktif')) {
    function koefisien_aktif(): float
    {
        $db = \Config\Database::connect();
        $th = tahun_aktif();
        if (!$th) return 0.0;
        $row = $db->table('koefisien_pkl')->where('tahun_id', $th->id)->get()->getRow();
        return $row ? (float)$row->koefisien : 0.0;
    }
}
