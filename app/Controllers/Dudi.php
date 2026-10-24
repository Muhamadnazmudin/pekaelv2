<?php

namespace App\Controllers;

use App\Models\DudiModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

class Dudi extends BaseController
{
    protected DudiModel $dudiModel;

    public function __construct()
    {
        $this->dudiModel = new DudiModel();
    }

    public function index()
    {
        $data['title'] = 'Data Mitra DUDI & MoU';
        $data['dudi'] = $this->dudiModel->orderBy('nama_dudi', 'ASC')->findAll();

        return template('admin/dudi/index', $data);
    }

    public function tambah()
    {
        $namaDudi   = trim($this->request->getPost('nama_dudi') ?? '');
        $bidang     = trim($this->request->getPost('bidang_usaha') ?? '');
        $alamat     = trim($this->request->getPost('alamat') ?? '');
        $nomorMou   = trim($this->request->getPost('nomor_mou') ?? '');
        $judulPks   = trim($this->request->getPost('judul_pks') ?? '');
        $pic        = trim($this->request->getPost('nama_pic') ?? '');
        $hpPic      = trim($this->request->getPost('no_hp_pic') ?? '');
        $status     = $this->request->getPost('status_kerjasama') ?: 'aktif';
        $lat        = trim($this->request->getPost('latitude') ?? '');
        $long       = trim($this->request->getPost('longitude') ?? '');
        $radius     = (int)($this->request->getPost('radius_meter') ?: 100);

        if (empty($namaDudi)) {
            return redirect()->to(site_url('dudi'))->with('error', 'Nama DUDI wajib diisi.');
        }

        $this->dudiModel->insert([
            'nama_dudi'        => $namaDudi,
            'bidang_usaha'     => $bidang,
            'alamat'           => $alamat,
            'nomor_mou'        => $nomorMou,
            'judul_pks'        => $judulPks,
            'nama_pic'         => $pic,
            'no_hp_pic'        => $hpPic,
            'latitude'         => !empty($lat) ? (float)$lat : null,
            'longitude'        => !empty($long) ? (float)$long : null,
            'radius_meter'     => $radius,
            'status_kerjasama' => $status
        ]);

        return redirect()->to(site_url('dudi'))->with('success', 'Data mitra DUDI berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $namaDudi   = trim($this->request->getPost('nama_dudi') ?? '');
        $bidang     = trim($this->request->getPost('bidang_usaha') ?? '');
        $alamat     = trim($this->request->getPost('alamat') ?? '');
        $nomorMou   = trim($this->request->getPost('nomor_mou') ?? '');
        $judulPks   = trim($this->request->getPost('judul_pks') ?? '');
        $pic        = trim($this->request->getPost('nama_pic') ?? '');
        $hpPic      = trim($this->request->getPost('no_hp_pic') ?? '');
        $status     = $this->request->getPost('status_kerjasama') ?: 'aktif';
        $lat        = trim($this->request->getPost('latitude') ?? '');
        $long       = trim($this->request->getPost('longitude') ?? '');
        $radius     = (int)($this->request->getPost('radius_meter') ?: 100);

        if (empty($namaDudi)) {
            return redirect()->to(site_url('dudi'))->with('error', 'Nama DUDI wajib diisi.');
        }

        $this->dudiModel->update($id, [
            'nama_dudi'        => $namaDudi,
            'bidang_usaha'     => $bidang,
            'alamat'           => $alamat,
            'nomor_mou'        => $nomorMou,
            'judul_pks'        => $judulPks,
            'nama_pic'         => $pic,
            'no_hp_pic'        => $hpPic,
            'latitude'         => !empty($lat) ? (float)$lat : null,
            'longitude'        => !empty($long) ? (float)$long : null,
            'radius_meter'     => $radius,
            'status_kerjasama' => $status
        ]);

        return redirect()->to(site_url('dudi'))->with('success', 'Data mitra DUDI berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $this->dudiModel->delete($id);
        return redirect()->to(site_url('dudi'))->with('success', 'Data mitra DUDI berhasil dihapus.');
    }

    public function import()
    {
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) {
            return redirect()->to(site_url('dudi'))->with('error', 'File Excel tidak valid atau belum dipilih.');
        }

        try {
            $reader = new Xlsx();
            $spreadsheet = $reader->load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $inserted = 0;
            $updated = 0;

            foreach ($sheet as $idx => $row) {
                if ($idx <= 1) continue;

                $namaDudi  = trim($row['A'] ?? '');
                $bidang    = trim($row['B'] ?? '');
                $alamat    = trim($row['C'] ?? '');
                $nomorMou  = trim($row['D'] ?? '');
                $judulPks  = trim($row['E'] ?? '');
                $pic       = trim($row['F'] ?? '');
                $hpPic     = trim($row['G'] ?? '');
                $status    = strtolower(trim($row['H'] ?? 'aktif'));

                if (empty($namaDudi)) continue;
                if (!in_array($status, ['aktif', 'nonaktif'])) $status = 'aktif';

                $cek = $this->dudiModel->where('nama_dudi', $namaDudi)->first();
                $data = [
                    'nama_dudi'        => $namaDudi,
                    'bidang_usaha'     => $bidang,
                    'alamat'           => $alamat,
                    'nomor_mou'        => $nomorMou,
                    'judul_pks'        => $judulPks,
                    'nama_pic'         => $pic,
                    'no_hp_pic'        => $hpPic,
                    'status_kerjasama' => $status
                ];

                if ($cek) {
                    $this->dudiModel->update($cek->id, $data);
                    $updated++;
                } else {
                    $this->dudiModel->insert($data);
                    $inserted++;
                }
            }

            return redirect()->to(site_url('dudi'))->with('success', "Import DUDI berhasil! Ditambahkan: $inserted, Diperbarui: $updated.");
        } catch (\Throwable $e) {
            return redirect()->to(site_url('dudi'))->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NAMA_DUDI');
        $sheet->setCellValue('B1', 'BIDANG_USAHA');
        $sheet->setCellValue('C1', 'ALAMAT');
        $sheet->setCellValue('D1', 'NOMOR_MOU');
        $sheet->setCellValue('E1', 'JUDUL_PKS');
        $sheet->setCellValue('F1', 'NAMA_PIC');
        $sheet->setCellValue('G1', 'NO_HP_PIC');
        $sheet->setCellValue('H1', 'STATUS (aktif/nonaktif)');

        $sheet->setCellValue('A2', 'PT. TELKOM INDONESIA');
        $sheet->setCellValue('B2', 'Telekomunikasi & Jaringan');
        $sheet->setCellValue('C2', 'Jl. Jenderal Sudirman No. 1');
        $sheet->setCellValue('D2', '421.5/001/SMK/2026');
        $sheet->setCellValue('E2', 'Kerjasama Program PKL dan Magang Industri');
        $sheet->setCellValue('F2', 'Budi Santoso');
        $sheet->setCellValue('G2', '081234567890');
        $sheet->setCellValue('H2', 'aktif');

        $writer = new XlsxWriter($spreadsheet);
        $fileName = 'Format_Import_DUDI_PEKAEL.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
