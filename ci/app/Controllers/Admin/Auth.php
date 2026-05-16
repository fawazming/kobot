<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\AuthService;

class Auth extends BaseController
{
    protected AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
        helper(['form', 'kobo']);
    }

    public function login()
    {
        if ($this->authService->isLoggedIn()) {
            return redirect()->to(base_url('admin'));
        }

        return view('admin/auth/login');
    }

    public function doLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $result = $this->authService->login($username, $password);

        if (!$result['status']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result['message']);
        }

        return redirect()->to(base_url('admin'))
            ->with('success', 'Welcome back, ' . $result['admin']['username'] . '!');
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect()->to(base_url('login'))
            ->with('success', 'You have been logged out successfully');
    }
}
