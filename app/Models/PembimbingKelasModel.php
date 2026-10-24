<?php

namespace App\Models;

use CodeIgniter\Model;

class PembimbingKelasModel extends Model
{
    protected $table            = 'pembimbing_pkl_perkelas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'guru_id',
        'kelas_id',
        'tahun_id',
        'total_jam',
        'koefisien',
        'jumlah_bimbingan',
        'created_at'
    ];

    public function getAllWithRelations(?int $tahunId = null)
    {
        $builder = $this->select('pembimbing_pkl_perkelas.*, guru.nama_guru, guru.nip, kelas.nama_kelas, tahun_ajaran.tahun')
                        ->join('guru', 'guru.id = pembimbing_pkl_perkelas.guru_id')
                        ->join('kelas', 'kelas.id = pembimbing_pkl_perkelas.kelas_id')
                        ->join('tahun_ajaran', 'tahun_ajaran.id = pembimbing_pkl_perkelas.tahun_id');

        if ($tahunId) {
            $builder->where('pembimbing_pkl_perkelas.tahun_id', $tahunId);
        }

        return $builder->orderBy('kelas.nama_kelas', 'ASC')
                       ->orderBy('guru.nama_guru', 'ASC')
                       ->findAll();
    }

    public function getSummaryPerGuru(int $tahunId)
    {
        return $this->select('guru.id as guru_id, guru.nama_guru, guru.nip, SUM(pembimbing_pkl_perkelas.total_jam) as total_jam, SUM(pembimbing_pkl_perkelas.jumlah_bimbingan) as kuota, SUM(pembimbing_pkl_perkelas.jumlah_bimbingan) as total_kuota')
                    ->join('guru', 'guru.id = pembimbing_pkl_perkelas.guru_id')
                    ->where('pembimbing_pkl_perkelas.tahun_id', $tahunId)
                    ->groupBy('guru.id, guru.nama_guru, guru.nip')
                    ->orderBy('guru.nama_guru', 'ASC')
                    ->findAll();
    }
}
