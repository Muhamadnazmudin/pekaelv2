# Pekael v2 (Sistem Informasi Praktik Kerja Lapangan)

Sistem Informasi Manajemen Praktik Kerja Lapangan (PKL) berbasis web yang telah ditingkatkan ke **CodeIgniter 4** dan berjalan di **PHP 8.3 (PHP 8.3.36)**.

## Spesifikasi & Teknologi
- **Framework:** CodeIgniter 4 (v4.7+)
- **Bahasa Pemrograman:** PHP >= 8.2 (Direkomendasikan PHP 8.3.36+)
- **Database:** MySQL / MariaDB
- **Web Server:** Apache / Nginx / Laragon / PHP Development Server

## Fitur Utama
- Manajemen Master Data (Siswa, Guru Pembimbing, DUDI / Perusahaan Mitra)
- Penempatan & Distribusi Siswa PKL (Manual & Proporsional)
- Monitoring & Log Absensi Siswa
- Monitoring Jurnal Kegiatan PKL Siswa beserta Bukti Foto
- Penilaian PKL & Monitoring Pembimbing
- Laporan Rekapitulasi PKL & Export (Excel, PDF)
- Tampilan Modern dengan Dukungan Mode Gelap (Dark Mode)

## Panduan Instalasi Lokal

1. **Clone Repository:**
   ```bash
   git clone https://github.com/Muhamadnazmudin/pekaelv2.git
   cd pekaelv2
   ```

2. **Install Dependensi Composer:**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment:**
   Salin file template `env` menjadi `.env`:
   ```bash
   copy env .env
   ```
   Buka file `.env` dan sesuaikan koneksi database serta base URL Anda:
   ```ini
   CI_ENVIRONMENT = development

   app.baseURL = 'http://pekael.test/'

   database.default.hostname = localhost
   database.default.database = pekael
   database.default.username = root
   database.default.password = 
   database.default.DBDriver = MySQLi
   database.default.port = 3306
   ```

4. **Generate Encryption Key:**
   ```bash
   php spark key:generate
   ```

5. **Jalankan Aplikasi:**
   - Melalui Laragon: Buka `http://pekael.test/`
   - Melalui CodeIgniter Spark:
     ```bash
     php spark serve
     ```

## Catatan Rilis v2
- Migrasi penuh arsitektur dari CodeIgniter 3 ke CodeIgniter 4.
- Kompatibilitas penuh dengan PHP 8.3.36+.
- Optimasi performa dan perbaikan antarmuka (UI/UX).
