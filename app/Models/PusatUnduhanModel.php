<?php

namespace App\Models;

use CodeIgniter\Model;

class PusatUnduhanModel extends Model
{
    protected $table            = 'pusat_unduhan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'judul',
        'kategori',
        'file',
        'ukuran_file',
        'created_at'
    ];
}
