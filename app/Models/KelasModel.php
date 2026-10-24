<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table            = 'kelas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_kelas',
        'tingkat',
        'status_pkl',
        'jumlah_siswa',
        'jurusan_id'
    ];

    public function getAllWithJurusan()
    {
        return $this->select('kelas.*, jurusan.nama_jurusan, jurusan.kode_jurusan')
                    ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                    ->orderBy('kelas.tingkat', 'DESC')
                    ->orderBy('kelas.nama_kelas', 'ASC')
                    ->findAll();
    }

    public function getKelasPkl()
    {
        return $this->select('kelas.*, jurusan.nama_jurusan, jurusan.kode_jurusan')
                    ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                    ->where('kelas.status_pkl', 'ya')
                    ->orderBy('kelas.nama_kelas', 'ASC')
                    ->findAll();
    }
}
