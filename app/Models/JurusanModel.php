<?php

namespace App\Models;

use CodeIgniter\Model;

class JurusanModel extends Model
{
    protected $table            = 'jurusan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_jurusan',
        'nama_jurusan',
        'singkatan',
        'kepala_program_id',
        'created_at'
    ];

    public function getAll()
    {
        return $this->select('jurusan.*, guru.nama_guru as nama_kaprog, guru.nip as nip_kaprog')
                    ->join('guru', 'guru.id = jurusan.kepala_program_id', 'left')
                    ->orderBy('jurusan.nama_jurusan', 'ASC')
                    ->findAll();
    }
}
