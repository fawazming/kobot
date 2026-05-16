<?php

namespace App\Models;

use CodeIgniter\Model;

class WebhookLogModel extends Model
{
    protected $table = 'webhook_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transaction_id', 'payload', 'signature', 'status', 'created_at',
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
}
