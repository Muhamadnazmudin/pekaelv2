<?php

namespace App\Models;

use CodeIgniter\Model;

class TahunModel extends Model
{
    protected $table            = 'tahun_ajaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tahun',
        'status'
    ];

    public function getAktif()
    {
        return $this->where('status', 'aktif')->first();
    }

    public function setAktif($id)
    {
        $this->db->transStart();
        $this->builder()->update(['status' => 'nonaktif']);
        $this->builder()->where('id', $id)->update(['status' => 'aktif']);
        $this->db->transComplete();
        return $this->db->transStatus();
    }
}
