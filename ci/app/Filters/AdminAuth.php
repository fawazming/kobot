<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->has('admin_id')) {
            return redirect()->to(base_url('login'));
        }

        $admin = session()->get('admin_data');
        if (!$admin || $admin['status'] !== 'active') {
            session()->destroy();
            return redirect()->to(base_url('login'))->with('error', 'Account is inactive');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
