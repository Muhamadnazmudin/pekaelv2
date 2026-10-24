<?php

namespace App\Models;

use CodeIgniter\Model;

class KoefisienModel extends Model
{
    protected $table            = 'koefisien_pkl';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tahun_id',
        'jumlah_siswa',
        'jumlah_rombel',
        'jp',
        'koefisien',
        'created_at'
    ];

    public function getAllWithTahun()
    {
        return $this->select('koefisien_pkl.*, tahun_ajaran.tahun')
                    ->join('tahun_ajaran', 'tahun_ajaran.id = koefisien_pkl.tahun_id')
                    ->orderBy('koefisien_pkl.id', 'DESC')
                    ->findAll();
    }

    public function getByTahun(int $tahunId)
    {
        return $this->where('tahun_id', $tahunId)->first();
    }

    /**
     * Perhitungan Koefisien PKL Akurat untuk Dapodik:
     * Formula: Koefisien = (Jumlah Rombel PKL * JP Wajib) / Jumlah Siswa PKL
     */
    public function hitung(int $tahunId, int $jp = 46): array
    {
        $db = \Config\Database::connect();

        // 1. Jumlah Rombel PKL aktif (status_pkl = 'ya')
        $jumlahRombel = $db->table('kelas')
                           ->where('status_pkl', 'ya')
                           ->countAllResults();

        // 2. Jumlah Siswa PKL (hanya siswa yang berada di kelas PKL pada tahun ajaran ini)
        $jumlahSiswa = $db->table('siswa')
                          ->join('kelas', 'kelas.id = siswa.id_kelas')
                          ->where('siswa.id_tahun', $tahunId)
                          ->where('kelas.status_pkl', 'ya')
                          ->countAllResults();

        // Fallback jika belum filter kelas, hitung seluruh siswa di tahun tsb
        if ($jumlahSiswa == 0) {
            $jumlahSiswa = $db->table('siswa')
                              ->where('id_tahun', $tahunId)
                              ->countAllResults();
        }

        // 3. Koefisien
        $koefisien = 0.0;
        if ($jumlahSiswa > 0) {
            $koefisien = ($jumlahRombel * $jp) / $jumlahSiswa;
        }

        return [
            'tahun_id'        => $tahunId,
            'jumlah_siswa'    => $jumlahSiswa,
            'jumlah_rombel'   => $jumlahRombel,
            'jp'              => $jp,
            'jp_wajib'        => $jp,
            'total_jam'       => ($jumlahRombel * $jp),
            'nilai_koefisien' => round($koefisien, 6),
            'koefisien'       => round($koefisien, 6)
        ];
    }
}
