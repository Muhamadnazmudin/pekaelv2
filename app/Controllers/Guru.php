<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\JurusanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

class Guru extends BaseController
{
    protected GuruModel $guruModel;
    protected JurusanModel $jurusanModel;

    public function __construct()
    {
        $this->guruModel = new GuruModel();
        $this->jurusanModel = new JurusanModel();
    }

    public function index()
    {
        $data['title'] = 'Data Guru / Tenaga Pendidik';
        $data['guru'] = $this->guruModel->getAllWithJurusan();
        $data['jurusan'] = $this->jurusanModel->getAll();

        return template('admin/guru/index', $data);
    }

    public function tambah()
    {
        $namaGuru  = trim($this->request->getPost('nama_guru') ?? '');
        $nip       = trim($this->request->getPost('nip') ?? '');
        $jurusanId = (int)($this->request->getPost('jurusan_id') ?? 0);
        $status    = $this->request->getPost('status') ?: 'aktif';
        $jenisGuru = $this->request->getPost('jenis_guru') ?: 'produktif';

        if (empty($namaGuru)) {
            return redirect()->to(site_url('guru'))->with('error', 'Nama guru wajib diisi.');
        }

        $this->guruModel->insert([
            'nama_guru'  => $namaGuru,
            'nip'        => $nip ?: '-',
            'jurusan_id' => $jurusanId,
            'status'     => $status,
            'jenis_guru' => $jenisGuru
        ]);

        return redirect()->to(site_url('guru'))->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $namaGuru  = trim($this->request->getPost('nama_guru') ?? '');
        $nip       = trim($this->request->getPost('nip') ?? '');
        $jurusanId = (int)($this->request->getPost('jurusan_id') ?? 0);
        $status    = $this->request->getPost('status') ?: 'aktif';
        $jenisGuru = $this->request->getPost('jenis_guru') ?: 'produktif';

        if (empty($namaGuru)) {
            return redirect()->to(site_url('guru'))->with('error', 'Nama guru wajib diisi.');
        }

        $this->guruModel->update($id, [
            'nama_guru'  => $namaGuru,
            'nip'        => $nip ?: '-',
            'jurusan_id' => $jurusanId,
            'status'     => $status,
            'jenis_guru' => $jenisGuru
        ]);

        return redirect()->to(site_url('guru'))->with('success', 'Data guru berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $this->guruModel->delete($id);
        return redirect()->to(site_url('guru'))->with('success', 'Data guru berhasil dihapus.');
    }

    public function import()
    {
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) {
            return redirect()->to(site_url('guru'))->with('error', 'File Excel tidak valid atau belum dipilih.');
        }

        try {
            $reader = new Xlsx();
            $spreadsheet = $reader->load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $inserted = 0;
            $updated = 0;

            foreach ($sheet as $idx => $row) {
                if ($idx <= 1) continue; // Skip header

                $nip       = trim($row['A'] ?? '');
                $namaGuru  = trim($row['B'] ?? '');
                $jenisGuru = strtolower(trim($row['C'] ?? 'produktif'));
                $status    = strtolower(trim($row['D'] ?? 'aktif'));

                if (empty($namaGuru)) continue;
                if (!in_array($jenisGuru, ['produktif', 'normatif', 'adaptif'])) $jenisGuru = 'produktif';
                if (!in_array($status, ['aktif', 'nonaktif'])) $status = 'aktif';

                $cek = null;
                if (!empty($nip) && $nip !== '-') {
                    $cek = $this->guruModel->where('nip', $nip)->first();
                }
                if (!$cek) {
                    $cek = $this->guruModel->where('nama_guru', $namaGuru)->first();
                }

                $data = [
                    'nip'        => $nip ?: '-',
                    'nama_guru'  => $namaGuru,
                    'jenis_guru' => $jenisGuru,
                    'status'     => $status
                ];

                if ($cek) {
                    $this->guruModel->update($cek->id, $data);
                    $updated++;
                } else {
                    $this->guruModel->insert($data);
                    $inserted++;
                }
            }

            return redirect()->to(site_url('guru'))->with('success', "Import Excel berhasil! Ditambahkan: $inserted, Diperbarui: $updated.");
        } catch (\Throwable $e) {
            return redirect()->to(site_url('guru'))->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NIP');
        $sheet->setCellValue('B1', 'NAMA_GURU');
        $sheet->setCellValue('C1', 'JENIS_GURU (produktif/normatif/adaptif)');
        $sheet->setCellValue('D1', 'STATUS (aktif/nonaktif)');

        // Contoh baris
        $sheet->setCellValue('A2', '198501012010011001');
        $sheet->setCellValue('B2', 'Budi Santoso, S.Kom');
        $sheet->setCellValue('C2', 'produktif');
        $sheet->setCellValue('D2', 'aktif');

        $writer = new XlsxWriter($spreadsheet);
        $fileName = 'Format_Import_Guru_PEKAEL.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
