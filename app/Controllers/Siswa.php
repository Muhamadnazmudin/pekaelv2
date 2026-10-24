<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\DudiModel;
use App\Models\TahunModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

class Siswa extends BaseController
{
    protected SiswaModel $siswaModel;
    protected KelasModel $kelasModel;
    protected DudiModel $dudiModel;
    protected TahunModel $tahunModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
        $this->dudiModel = new DudiModel();
        $this->tahunModel = new TahunModel();
    }

    public function index()
    {
        $tahunAktif = $this->tahunModel->getAktif();
        $tahunId = $this->request->getGet('tahun_id') ?: ($tahunAktif ? $tahunAktif->id : null);
        $kelasId = $this->request->getGet('kelas_id') ?: null;

        $data['title']       = 'Data Siswa Peserta PKL';
        $data['siswa']       = $this->siswaModel->getAllWithRelations($tahunId ? (int)$tahunId : null, $kelasId ? (int)$kelasId : null);
        $data['kelas']       = $this->kelasModel->orderBy('nama_kelas', 'ASC')->findAll();
        $data['dudi']        = $this->dudiModel->getAktif();
        $data['tahun_list']  = $this->tahunModel->orderBy('tahun', 'DESC')->findAll();
        $data['tahun_pilih'] = $tahunId;
        $data['kelas_pilih'] = $kelasId;

        return template('admin/siswa/index', $data);
    }

    public function tambah()
    {
        $nisn      = trim($this->request->getPost('nisn') ?? '');
        $nama      = trim($this->request->getPost('nama') ?? '');
        $kelasId   = (int)($this->request->getPost('id_kelas') ?? 0);
        $dudiId    = (int)($this->request->getPost('dudi_id') ?? 0);
        $tahunId   = (int)($this->request->getPost('id_tahun') ?? 0);
        $jk        = $this->request->getPost('jenis_kelamin') ?: 'L';
        $tmpLahir  = trim($this->request->getPost('tempat_lahir') ?? '');
        $tglLahir  = $this->request->getPost('tanggal_lahir') ?: null;
        $alamat    = trim($this->request->getPost('alamat') ?? '');

        if (empty($nama)) {
            return redirect()->to(site_url('siswa'))->with('error', 'Nama siswa wajib diisi.');
        }

        $this->siswaModel->insert([
            'nisn'              => $nisn,
            'nama'              => $nama,
            'id_kelas'          => $kelasId,
            'dudi_id'           => $dudiId ?: null,
            'id_tahun'          => $tahunId,
            'jenis_kelamin'     => $jk,
            'tempat_lahir'      => $tmpLahir,
            'tanggal_lahir'     => $tglLahir,
            'alamat'            => $alamat,
            'status_verifikasi' => 'diterima'
        ]);

        return redirect()->to(site_url('siswa'))->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $nisn      = trim($this->request->getPost('nisn') ?? '');
        $nama      = trim($this->request->getPost('nama') ?? '');
        $kelasId   = (int)($this->request->getPost('id_kelas') ?? 0);
        $dudiId    = (int)($this->request->getPost('dudi_id') ?? 0);
        $tahunId   = (int)($this->request->getPost('id_tahun') ?? 0);
        $jk        = $this->request->getPost('jenis_kelamin') ?: 'L';
        $tmpLahir  = trim($this->request->getPost('tempat_lahir') ?? '');
        $tglLahir  = $this->request->getPost('tanggal_lahir') ?: null;
        $alamat    = trim($this->request->getPost('alamat') ?? '');

        if (empty($nama)) {
            return redirect()->to(site_url('siswa'))->with('error', 'Nama siswa wajib diisi.');
        }

        $this->siswaModel->update($id, [
            'nisn'          => $nisn,
            'nama'          => $nama,
            'id_kelas'      => $kelasId,
            'dudi_id'       => $dudiId ?: null,
            'id_tahun'      => $tahunId,
            'jenis_kelamin' => $jk,
            'tempat_lahir'  => $tmpLahir,
            'tanggal_lahir' => $tglLahir,
            'alamat'        => $alamat
        ]);

        return redirect()->to(site_url('siswa'))->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $this->siswaModel->delete($id);
        return redirect()->to(site_url('siswa'))->with('success', 'Data siswa berhasil dihapus.');
    }

    public function import()
    {
        $file = $this->request->getFile('file_excel');
        $tahunId = (int)($this->request->getPost('id_tahun') ?? 0);

        if (!$tahunId) {
            $th = $this->tahunModel->getAktif();
            $tahunId = $th ? $th->id : 0;
        }

        if (!$file || !$file->isValid()) {
            return redirect()->to(site_url('siswa'))->with('error', 'File Excel tidak valid atau belum dipilih.');
        }

        try {
            $reader = new Xlsx();
            $spreadsheet = $reader->load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // Lookup kelas
            $kelasList = $this->kelasModel->findAll();
            $kelasMap = [];
            foreach ($kelasList as $k) {
                $kelasMap[strtoupper(trim($k->nama_kelas))] = $k->id;
            }

            $inserted = 0;
            $updated = 0;

            foreach ($sheet as $idx => $row) {
                if ($idx <= 1) continue;

                $nisn      = trim($row['A'] ?? '');
                $nama      = trim($row['B'] ?? '');
                $kelasNama = strtoupper(trim($row['C'] ?? ''));
                $jk        = strtoupper(trim($row['D'] ?? 'L'));
                $tmpLahir  = trim($row['E'] ?? '');
                $tglLahir  = trim($row['F'] ?? '');
                $alamat    = trim($row['G'] ?? '');

                if (empty($nama)) continue;
                if (!in_array($jk, ['L', 'P'])) $jk = 'L';

                $kelasId = $kelasMap[$kelasNama] ?? 0;

                $data = [
                    'nisn'              => $nisn,
                    'nama'              => $nama,
                    'id_kelas'          => $kelasId,
                    'id_tahun'          => $tahunId,
                    'jenis_kelamin'     => $jk,
                    'tempat_lahir'      => $tmpLahir,
                    'tanggal_lahir'     => !empty($tglLahir) ? date('Y-m-d', strtotime($tglLahir)) : null,
                    'alamat'            => $alamat,
                    'status_verifikasi' => 'diterima'
                ];

                $cek = null;
                if (!empty($nisn)) {
                    $cek = $this->siswaModel->where('nisn', $nisn)->where('id_tahun', $tahunId)->first();
                }
                if (!$cek) {
                    $cek = $this->siswaModel->where('nama', $nama)->where('id_tahun', $tahunId)->first();
                }

                if ($cek) {
                    $this->siswaModel->update($cek->id, $data);
                    $updated++;
                } else {
                    $this->siswaModel->insert($data);
                    $inserted++;
                }
            }

            return redirect()->to(site_url('siswa'))->with('success', "Import data siswa berhasil! Ditambahkan: $inserted, Diperbarui: $updated.");
        } catch (\Throwable $e) {
            return redirect()->to(site_url('siswa'))->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NISN');
        $sheet->setCellValue('B1', 'NAMA_SISWA');
        $sheet->setCellValue('C1', 'NAMA_KELAS');
        $sheet->setCellValue('D1', 'JENIS_KELAMIN (L/P)');
        $sheet->setCellValue('E1', 'TEMPAT_LAHIR');
        $sheet->setCellValue('F1', 'TANGGAL_LAHIR (YYYY-MM-DD)');
        $sheet->setCellValue('G1', 'ALAMAT');

        $sheet->setCellValue('A2', '0061234567');
        $sheet->setCellValue('B2', 'Ahmad Fauzi');
        $sheet->setCellValue('C2', 'XII RPL 1');
        $sheet->setCellValue('D2', 'L');
        $sheet->setCellValue('E2', 'Kuningan');
        $sheet->setCellValue('F2', '2007-05-14');
        $sheet->setCellValue('G2', 'Jl. Siliwangi No. 10');

        $writer = new XlsxWriter($spreadsheet);
        $fileName = 'Format_Import_Siswa_PEKAEL.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
