<?php

namespace App\Models;

use CodeIgniter\Model;

class SekolahModel extends Model
{
    protected $table            = 'sekolah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_sekolah',
        'npsn',
        'alamat',
        'kepala_sekolah',
        'nip_kepsek',
        'logo'
    ];

    public function getProfil()
    {
        $data = $this->first();
        if (!$data) {
            $this->insert([
                'nama_sekolah'   => 'SMK Negeri 1',
                'npsn'           => '',
                'alamat'         => '',
                'kepala_sekolah' => '',
                'nip_kepsek'     => '',
                'logo'           => ''
            ]);
            return $this->first();
        }
        return $data;
    }
}
