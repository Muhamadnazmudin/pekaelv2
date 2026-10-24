<?php

namespace App\Models;

use CodeIgniter\Model;

class DistribusiManualModel extends Model
{
    protected $table            = 'distribusi_manual';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'guru_id',
        'siswa_id',
        'kelas_id',
        'tahun_id',
        'created_at'
    ];

    public function getAllWithRelations(?int $tahunId = null)
    {
        $builder = $this->select('distribusi_manual.*, siswa.nama as nama_siswa, siswa.nisn, guru.nama_guru, guru.nip, kelas.nama_kelas, dudi.nama_dudi, dudi.nomor_mou, dudi.judul_pks')
                        ->join('siswa', 'siswa.id = distribusi_manual.siswa_id')
                        ->join('guru', 'guru.id = distribusi_manual.guru_id')
                        ->join('kelas', 'kelas.id = distribusi_manual.kelas_id')
                        ->join('dudi', 'dudi.id = siswa.dudi_id', 'left');

        if ($tahunId) {
            $builder->where('distribusi_manual.tahun_id', $tahunId);
        }

        return $builder->orderBy('kelas.nama_kelas', 'ASC')
                       ->orderBy('siswa.nama', 'ASC')
                       ->findAll();
    }

    public function getSelectedIds(int $guruId, int $kelasId, int $tahunId)
    {
        $rows = $this->select('siswa_id')
                     ->where('guru_id', $guruId)
                     ->where('kelas_id', $kelasId)
                     ->where('tahun_id', $tahunId)
                     ->findAll();
        return array_map(fn($r) => (int)$r->siswa_id, $rows);
    }
}
