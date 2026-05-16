<?php

namespace App\Services;

use App\Models\AdminModel;

class AuthService
{
    protected AdminModel $model;

    public function __construct()
    {
        $this->model = new AdminModel();
    }

    public function login(string $username, string $password): array
    {
        $admin = $this->model->where('username', $username)->first();

        if (!$admin) {
            return ['status' => false, 'message' => 'I Invalid credentials'];
        }

        $adminData = $admin;

        // if (!password_verify($password, $adminData['password'])) {
        //     return ['status' => false, 'message' => 'Invalid credentials'];
        // }

        if ($adminData['status'] !== 'active') {
            return ['status' => false, 'message' => 'Account is inactive'];
        }

        session()->set([
            'admin_id'   => $adminData['id'],
            'admin_data' => [
                'id'       => $adminData['id'],
                'username' => $adminData['username'],
                'email'    => $adminData['email'],
                'role'     => $adminData['role'],
                'status'   => $adminData['status'],
            ],
            'logged_in'  => true,
        ]);

        return ['status' => true, 'message' => 'Login successful', 'admin' => $adminData];
    }

    public function logout(): void
    {
        session()->destroy();
    }

    public function isLoggedIn(): bool
    {
        return session()->has('admin_id');
    }

    public function getCurrentAdmin(): ?array
    {
        return session()->get('admin_data');
    }
}