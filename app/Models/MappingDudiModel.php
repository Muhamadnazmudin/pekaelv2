<?php

namespace App\Models;

use CodeIgniter\Model;

class MappingDudiModel extends Model
{
    protected $table            = 'mapping_dudi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'siswa_id',
        'dudi_id',
        'guru_id',
        'tahun_id',
        'created_at'
    ];

    public function getAllWithRelations(?int $tahunId = null)
    {
        $builder = $this->select('mapping_dudi.*, siswa.nama as nama_siswa, siswa.nisn, kelas.nama_kelas, dudi.nama_dudi, guru.nama_guru')
                        ->join('siswa', 'siswa.id = mapping_dudi.siswa_id')
                        ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
                        ->join('dudi', 'dudi.id = mapping_dudi.dudi_id')
                        ->join('guru', 'guru.id = mapping_dudi.guru_id', 'left');

        if ($tahunId) {
            $builder->where('mapping_dudi.tahun_id', $tahunId);
        }

        return $builder->orderBy('dudi.nama_dudi', 'ASC')
                       ->orderBy('siswa.nama', 'ASC')
                       ->findAll();
    }
}
