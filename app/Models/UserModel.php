<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_lengkap',
        'username',
        'password',
        'role',
        'created_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = false;

    public function getByUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }
}
