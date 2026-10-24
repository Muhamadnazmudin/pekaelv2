<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table            = 'guru';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nip',
        'nama_guru',
        'jurusan_id',
        'status',
        'jenis_guru',
        'created_at'
    ];

    public function getAll()
    {
        return $this->orderBy('nama_guru', 'ASC')->findAll();
    }

    public function getAllWithJurusan()
    {
        return $this->select('guru.*, jurusan.nama_jurusan')
                    ->join('jurusan', 'jurusan.id = guru.jurusan_id', 'left')
                    ->orderBy('guru.nama_guru', 'ASC')
                    ->findAll();
    }

    public function getAktif()
    {
        return $this->where('status', 'aktif')
                    ->orderBy('nama_guru', 'ASC')
                    ->findAll();
    }
}
