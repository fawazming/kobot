<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BusinessSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('businesses')->insert([
            'business_id'    => 'BUS_001',
            'name'           => 'KoboTrack Demo Business',
            'phone'          => '+2348000000001',
            'email'          => 'demo@kobotrack.com',
            'public_key'     => 'pk_live_' . bin2hex(random_bytes(24)),
            'secret_key'     => 'sk_live_' . bin2hex(random_bytes(24)),
            'webhook_secret' => 'whsec_' . bin2hex(random_bytes(24)),
            'status'         => 'active',
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        echo "Demo business created\n";
    }
}
