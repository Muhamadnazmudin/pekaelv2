<?php

namespace App\Controllers;

use App\Models\TahunModel;
use App\Models\SekolahModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

class RekapPkl extends BaseController
{
    public function index()
    {
        $tahunModel = new TahunModel();
        $sekolahModel = new SekolahModel();

        $tahun = $tahunModel->getAktif();
        $tahunId = $tahun ? $tahun->id : 0;

        $rekap = $this->db->table('distribusi_manual')
            ->select('distribusi_manual.id, siswa.nama as nama_siswa, siswa.nisn, kelas.nama_kelas, guru.nama_guru as nama_pembimbing, guru.nip as nip_pembimbing, dudi.nama_dudi, dudi.nomor_mou, dudi.judul_pks')
            ->join('siswa', 'siswa.id = distribusi_manual.siswa_id')
            ->join('guru', 'guru.id = distribusi_manual.guru_id')
            ->join('kelas', 'kelas.id = distribusi_manual.kelas_id')
            ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
            ->where('distribusi_manual.tahun_id', $tahunId)
            ->orderBy('kelas.nama_kelas', 'ASC')
            ->orderBy('siswa.nama', 'ASC')
            ->get()->getResult();

        $data['title']   = 'Rekapitulasi PKL & Alokasi Pembimbing (Dapodik Ready)';
        $data['tahun']   = $tahun;
        $data['sekolah'] = $sekolahModel->getProfil();
        $data['rekap']   = $rekap;

        return template('admin/rekap/index', $data);
    }

    public function export_excel()
    {
        $tahunModel = new TahunModel();
        $sekolahModel = new SekolahModel();

        $tahun = $tahunModel->getAktif();
        $tahunId = $tahun ? $tahun->id : 0;
        $sekolah = $sekolahModel->getProfil();

        $rekap = $this->db->table('distribusi_manual')
            ->select('siswa.nama as nama_siswa, siswa.nisn, kelas.nama_kelas, guru.nama_guru as nama_pembimbing, guru.nip as nip_pembimbing, dudi.nama_dudi, dudi.nomor_mou, dudi.judul_pks')
            ->join('siswa', 'siswa.id = distribusi_manual.siswa_id')
            ->join('guru', 'guru.id = distribusi_manual.guru_id')
            ->join('kelas', 'kelas.id = distribusi_manual.kelas_id')
            ->join('dudi', 'dudi.id = siswa.dudi_id', 'left')
            ->where('distribusi_manual.tahun_id', $tahunId)
            ->orderBy('kelas.nama_kelas', 'ASC')
            ->orderBy('siswa.nama', 'ASC')
            ->get()->getResult();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Title Header
        $sheet->setCellValue('A1', 'REKAPITULASI PEMBAGIAN PEMBIMBING PKL UNTUK INPUT DAPODIK');
        $sheet->setCellValue('A2', 'SEKOLAH: ' . ($sekolah->nama_sekolah ?? 'SMK') . ' | TAHUN AJARAN: ' . ($tahun->tahun ?? '-'));

        $sheet->setCellValue('A4', 'NO');
        $sheet->setCellValue('B4', 'NISN');
        $sheet->setCellValue('C4', 'NAMA SISWA');
        $sheet->setCellValue('D4', 'KELAS / ROMBEL');
        $sheet->setCellValue('E4', 'GURU PEMBIMBING');
        $sheet->setCellValue('F4', 'NIP PEMBIMBING');
        $sheet->setCellValue('G4', 'MITRA DUDI');
        $sheet->setCellValue('H4', 'NOMOR MOU');
        $sheet->setCellValue('I4', 'JUDUL PKS / KERJASAMA');

        $rowNum = 5;
        $no = 1;
        foreach ($rekap as $r) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValueExplicit('B' . $rowNum, $r->nisn ?: '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $rowNum, $r->nama_siswa);
            $sheet->setCellValue('D' . $rowNum, $r->nama_kelas);
            $sheet->setCellValue('E' . $rowNum, $r->nama_pembimbing);
            $sheet->setCellValueExplicit('F' . $rowNum, $r->nip_pembimbing ?: '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('G' . $rowNum, $r->nama_dudi ?: 'Belum Ditempatkan');
            $sheet->setCellValue('H' . $rowNum, $r->nomor_mou ?: '-');
            $sheet->setCellValue('I' . $rowNum, $r->judul_pks ?: '-');
            $rowNum++;
        }

        // Auto column width
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new XlsxWriter($spreadsheet);
        $fileName = 'Rekap_PKL_Dapodik_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
