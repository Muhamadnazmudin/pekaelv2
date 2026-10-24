<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data['title'] = 'Manajemen Pengguna / Operator';
        $data['users'] = $this->userModel->orderBy('username', 'ASC')->findAll();

        return template('admin/users/index', $data);
    }

    public function tambah()
    {
        $username = trim($this->request->getPost('username') ?? '');
        $nama     = trim($this->request->getPost('nama_lengkap') ?? '');
        $password = trim($this->request->getPost('password') ?? '');
        $role     = $this->request->getPost('role') ?: 'admin';

        if (empty($username) || empty($password)) {
            return redirect()->to(site_url('users'))->with('error', 'Username dan password wajib diisi.');
        }

        $cek = $this->userModel->where('username', $username)->first();
        if ($cek) {
            return redirect()->to(site_url('users'))->with('error', 'Username sudah digunakan.');
        }

        $this->userModel->insert([
            'username'     => $username,
            'nama_lengkap' => $nama ?: $username,
            'password'     => password_hash($password, PASSWORD_DEFAULT),
            'role'         => $role
        ]);

        return redirect()->to(site_url('users'))->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $nama     = trim($this->request->getPost('nama_lengkap') ?? '');
        $password = trim($this->request->getPost('password') ?? '');
        $role     = $this->request->getPost('role') ?: 'admin';

        $data = [
            'nama_lengkap' => $nama,
            'role'         => $role
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);

        return redirect()->to(site_url('users'))->with('success', 'Data user berhasil diperbarui.');
    }

    public function hapus($id)
    {
        // Cegah menghapus user sendiri
        if ((int)$this->session->get('user_id') === (int)$id) {
            return redirect()->to(site_url('users'))->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $this->userModel->delete($id);
        return redirect()->to(site_url('users'))->with('success', 'User berhasil dihapus.');
    }
}
