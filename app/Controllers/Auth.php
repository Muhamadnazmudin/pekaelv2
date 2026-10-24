<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        $session = session();
        if ($session->get('logged_in')) {
            $role = $session->get('role') ?: 'admin';
            if ($role === 'siswa') {
                return redirect()->to('siswa/dashboard');
            } elseif ($role === 'guru') {
                return redirect()->to('guru/dashboard');
            }
            return redirect()->to('dashboard');
        }

        return view('auth/login', [
            'title' => 'Login Sistem PKL'
        ]);
    }

    public function process()
    {
        $session = session();
        $username = trim($this->request->getPost('username') ?? '');
        $password = trim($this->request->getPost('password') ?? '');

        if (empty($username) || empty($password)) {
            return redirect()->to('login')->with('error', 'Silakan masukkan username dan password.');
        }

        // 1. Cek User Administrator / Operator Sekolah
        $user = $this->db->table('users')->where('username', $username)->get()->getRow();
        if ($user) {
            $valid = false;
            if (password_verify($password, $user->password)) {
                $valid = true;
            } elseif (md5($password) === $user->password || $password === $user->password) {
                // Legacy CI3 migration fallback
                $valid = true;
            }

            if ($valid) {
                $session->set([
                    'id'        => $user->id,
                    'username'  => $user->username,
                    'nama'      => $user->nama ?? $user->username,
                    'role'      => 'admin',
                    'logged_in' => true
                ]);
                return redirect()->to('dashboard');
            }
        }

        // 2. Cek Siswa (Username = NISN, Password = NISN)
        $siswa = $this->db->table('siswa')->where('nisn', $username)->get()->getRow();
        if ($siswa) {
            $validSiswa = false;
            if (!empty($siswa->password)) {
                if (password_verify($password, $siswa->password) || $password === $siswa->password) {
                    $validSiswa = true;
                }
            } else {
                // Default: Password adalah NISN
                if ($password === $siswa->nisn) {
                    $validSiswa = true;
                }
            }

            if ($validSiswa) {
                $session->set([
                    'id'        => $siswa->id,
                    'siswa_id'  => $siswa->id,
                    'username'  => $siswa->nisn,
                    'nama'      => $siswa->nama,
                    'role'      => 'siswa',
                    'logged_in' => true
                ]);
                return redirect()->to('siswa/dashboard');
            }
        }

        // 3. Cek Guru (Login NIP / Nama Lengkap Tanpa Spasi)
        $cleanInput = strtolower(str_replace(' ', '', $username));
        $guru = $this->db->table('guru')
            ->groupStart()
                ->where('nip', $username)
                ->orWhere('LOWER(REPLACE(nama_guru, " ", ""))', $cleanInput)
            ->groupEnd()
            ->get()->getRow();

        if ($guru) {
            $validGuru = false;

            if (!empty($guru->password)) {
                if (password_verify($password, $guru->password) || $password === $guru->password) {
                    $validGuru = true;
                }
            } else {
                // Default: Password sama dengan NIP atau Nama Lengkap disatukan
                $cleanInputPass = strtolower(str_replace(' ', '', $password));
                if ($password === $guru->nip || $cleanInputPass === strtolower(str_replace(' ', '', $guru->nama_guru))) {
                    $validGuru = true;
                }
            }

            if ($validGuru) {
                // Cek apakah guru adalah pembimbing PKL (di distribusi manual, distribusi proporsional, atau mapping dudi)
                $countDm  = $this->db->table('distribusi_manual')->where('guru_id', $guru->id)->countAllResults();
                $countDp  = $this->db->table('distribusi_pkl')->where('guru_id', $guru->id)->countAllResults();
                $countMap = $this->db->table('mapping_dudi')->where('guru_id', $guru->id)->countAllResults();
                $isPembimbing = ($countDm > 0 || $countDp > 0 || $countMap > 0);

                // Cek apakah guru adalah Kepala Program Keahlian (Kaprog)
                $kaprogJurusan = $this->db->table('jurusan')->where('kepala_program_id', $guru->id)->get()->getResult();
                $isKaprog = !empty($kaprogJurusan);
                $kaprogJurusanIds = !empty($kaprogJurusan) ? array_column($kaprogJurusan, 'id') : [];

                $session->set([
                    'id'               => $guru->id,
                    'guru_id'          => $guru->id,
                    'username'         => !empty($guru->nip) ? $guru->nip : strtolower(str_replace(' ', '', $guru->nama_guru)),
                    'nama'             => $guru->nama_guru,
                    'role'             => 'guru',
                    'is_pembimbing'    => $isPembimbing,
                    'is_kaprog'        => $isKaprog,
                    'kaprog_jurusans'  => $kaprogJurusanIds,
                    'logged_in'        => true
                ]);

                return redirect()->to('guru/dashboard');
            }
        }

        return redirect()->to('login')->with('error', 'Username atau password tidak sesuai.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login')->with('success', 'Anda telah berhasil logout.');
    }
}
