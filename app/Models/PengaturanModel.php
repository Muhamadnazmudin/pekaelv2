<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaturanModel extends Model
{
    protected $table            = 'pengaturan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_pengaturan',
        'value',
        'tema'
    ];

    public function getSetting(string $key, string $default = '')
    {
        $row = $this->where('nama_pengaturan', $key)->first();
        return $row ? $row->value : $default;
    }

    public function setSetting(string $key, string $value)
    {
        $row = $this->where('nama_pengaturan', $key)->first();
        if ($row) {
            return $this->update($row->id, ['value' => $value]);
        }
        return $this->insert(['nama_pengaturan' => $key, 'value' => $value]);
    }

    public function getJamPkl(): int
    {
        $db = \Config\Database::connect();
        $row = $db->table('pengaturan_pkl')->limit(1)->get()->getRow();
        return $row && !empty($row->jam_pkl) ? (int)$row->jam_pkl : 46;
    }

    public function setJamPkl(int $jam, ?int $tahunId = null)
    {
        $db = \Config\Database::connect();
        $row = $db->table('pengaturan_pkl')->limit(1)->get()->getRow();
        if ($row) {
            return $db->table('pengaturan_pkl')
                      ->where('id', $row->id)
                      ->update(['jam_pkl' => $jam, 'tahun_ajaran_id' => $tahunId]);
        }
        return $db->table('pengaturan_pkl')
                  ->insert(['jam_pkl' => $jam, 'tahun_ajaran_id' => $tahunId]);
    }

    public function getMetode(): string
    {
        $metode = $this->getSetting('metode_perhitungan', 'koefisien');
        return in_array($metode, ['koefisien', 'proporsional']) ? $metode : 'koefisien';
    }

    public function setMetode(string $metode)
    {
        if (!in_array($metode, ['koefisien', 'proporsional'])) {
            $metode = 'koefisien';
        }
        return $this->setSetting('metode_perhitungan', $metode);
    }

    public function getTema(): string
    {
        $tema = $this->getSetting('tema_aplikasi', 'light');
        return in_array($tema, ['light', 'dark']) ? $tema : 'light';
    }

    public function setTema(string $tema)
    {
        if (!in_array($tema, ['light', 'dark'])) {
            $tema = 'light';
        }
        return $this->setSetting('tema_aplikasi', $tema);
    }
}
