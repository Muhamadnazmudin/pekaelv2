<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nisn',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'id_kelas',
        'dudi_id',
        'id_tahun',
        'nama_ortu',
        'nis',
        'foto',
        'bukti_upload',
        'status_verifikasi',
        'nomor_ijazah'
    ];

    public function getAllWithRelations(?int $tahunId = null, ?int $kelasId = null)
    {
        $builder = $this->select('siswa.*, kelas.nama_kelas, kelas.tingkat, dudi.nama_dudi, dudi.nomor_mou, dudi.judul_pks, tahun_ajaran.tahun')
                        ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
                        ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
                        ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.id_tahun', 'left');

        if ($tahunId) {
            $builder->where('siswa.id_tahun', $tahunId);
        }
        if ($kelasId) {
            $builder->where('siswa.id_kelas', $kelasId);
        }

        return $builder->orderBy('kelas.nama_kelas', 'ASC')
                       ->orderBy('siswa.nama', 'ASC')
                       ->findAll();
    }

    public function getSiswaPkl(int $tahunId)
    {
        return $this->select('siswa.*, kelas.nama_kelas, dudi.nama_dudi')
                    ->join('kelas', 'kelas.id = siswa.id_kelas')
                    ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
                    ->where('siswa.id_tahun', $tahunId)
                    ->where('kelas.status_pkl', 'ya')
                    ->orderBy('kelas.nama_kelas', 'ASC')
                    ->orderBy('siswa.nama', 'ASC')
                    ->findAll();
    }
}
