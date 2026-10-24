<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = $session->get('role') ?: 'admin';
        $requiredRole = !empty($arguments[0]) ? $arguments[0] : 'admin';

        if ($requiredRole === 'admin' && $userRole !== 'admin') {
            if ($userRole === 'siswa') {
                return redirect()->to('siswa/dashboard');
            }
            if ($userRole === 'guru') {
                return redirect()->to('guru/dashboard');
            }
            return redirect()->to('login')->with('error', 'Akses tidak diizinkan.');
        }

        if ($requiredRole === 'siswa' && $userRole !== 'siswa') {
            if ($userRole === 'admin') {
                return redirect()->to('dashboard');
            }
            if ($userRole === 'guru') {
                return redirect()->to('guru/dashboard');
            }
        }

        if ($requiredRole === 'guru' && $userRole !== 'guru') {
            if ($userRole === 'admin') {
                return redirect()->to('dashboard');
            }
            if ($userRole === 'siswa') {
                return redirect()->to('siswa/dashboard');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
