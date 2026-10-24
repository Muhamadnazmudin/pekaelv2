<?php

namespace App\Models;

use CodeIgniter\Model;

class DudiModel extends Model
{
    protected $table            = 'dudi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_dudi',
        'bidang_usaha',
        'alamat',
        'desa_kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
        'nomor_mou',
        'judul_pks',
        'nama_pic',
        'no_hp_pic',
        'latitude',
        'longitude',
        'radius_meter',
        'status_kerjasama',
        'created_at'
    ];

    public function getAktif()
    {
        return $this->where('status_kerjasama', 'aktif')
                    ->orderBy('nama_dudi', 'ASC')
                    ->findAll();
    }
}
