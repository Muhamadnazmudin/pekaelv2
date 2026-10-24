<?php

namespace App\Models;

use CodeIgniter\Model;

class PembagianJamModel extends Model
{
    protected $table            = 'pembagian_jam';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'guru_id',
        'kelas_id',
        'tahun_id',
        'jumlah_jam',
        'created_at'
    ];

    public function getAllWithRelations(?int $tahunId = null)
    {
        $builder = $this->select('pembagian_jam.*, guru.nama_guru, guru.nip, kelas.nama_kelas, tahun_ajaran.tahun')
                        ->join('guru', 'guru.id = pembagian_jam.guru_id')
                        ->join('kelas', 'kelas.id = pembagian_jam.kelas_id')
                        ->join('tahun_ajaran', 'tahun_ajaran.id = pembagian_jam.tahun_id');

        if ($tahunId) {
            $builder->where('pembagian_jam.tahun_id', $tahunId);
        }

        return $builder->orderBy('kelas.nama_kelas', 'ASC')
                       ->orderBy('pembagian_jam.jumlah_jam', 'DESC')
                       ->findAll();
    }

    public function getRecapPerKelas(int $tahunId)
    {
        return $this->select('kelas.id as kelas_id, kelas.nama_kelas, SUM(pembagian_jam.jumlah_jam) as total_jam, COUNT(DISTINCT pembagian_jam.guru_id) as jumlah_guru')
                    ->join('kelas', 'kelas.id = pembagian_jam.kelas_id')
                    ->where('pembagian_jam.tahun_id', $tahunId)
                    ->groupBy('kelas.id, kelas.nama_kelas')
                    ->orderBy('kelas.nama_kelas', 'ASC')
                    ->findAll();
    }

    public function getGuruInKelas(int $kelasId, int $tahunId)
    {
        return $this->select('pembagian_jam.guru_id, guru.nama_guru, guru.nip, SUM(pembagian_jam.jumlah_jam) as total_jam')
                    ->join('guru', 'guru.id = pembagian_jam.guru_id')
                    ->where('pembagian_jam.kelas_id', $kelasId)
                    ->where('pembagian_jam.tahun_id', $tahunId)
                    ->groupBy('pembagian_jam.guru_id, guru.nama_guru, guru.nip')
                    ->orderBy('total_jam', 'DESC')
                    ->findAll();
    }
}
