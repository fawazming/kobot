<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrationModel extends Model
{
    protected $table = 'registrations';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'registration_id', 'transaction_id', 'json_data',
        'created_at', 'updated_at',
    ];
    protected $useTimestamps = true;
}
