<?php

namespace App\Models;

use CodeIgniter\Model;

class BusinessModel extends Model
{
    protected $table = 'businesses';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'business_id', 'name', 'phone', 'email',
        'public_key', 'secret_key', 'webhook_secret',
        'status', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = false;
}
