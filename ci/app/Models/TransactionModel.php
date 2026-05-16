<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transaction_id', 'business_id', 'email',
        'original_amount', 'payable_amount', 'payment_status',
        'registration_id', 'webhook_verified', 'metadata',
        'created_at', 'updated_at',
    ];
    protected $useTimestamps = true;
}
