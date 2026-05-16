<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('admins')->insert([
            'username'   => 'admin',
            'email'      => 'admin@kobotrack.com',
            'password'   => password_hash('admin123', PASSWORD_BCRYPT),
            'role'       => 'superadmin',
            'status'     => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        echo "Admin user created: admin / admin123\n";
    }
}
