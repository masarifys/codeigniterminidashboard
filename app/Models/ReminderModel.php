<?php

namespace App\Models;

use CodeIgniter\Model;

class ReminderModel extends Model
{
    protected $table            = 'reminders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'service_id', 'type', 'reminder_date', 'message', 'is_sent', 'sent_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id' => 'required|integer',
        'type' => 'required|in_list[domain_renewal,hosting_renewal,ssl_expiry,invoice_due,maintenance_due]',
        'reminder_date' => 'required|valid_date',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getPendingReminders()
    {
        return $this->where('is_sent', 0)
                    ->where('reminder_date <=', date('Y-m-d'))
                    ->findAll();
    }

    public function getUserReminders($userId, $limit = 5)
    {
        return $this->where('user_id', $userId)
                    ->where('is_sent', 0)
                    ->where('reminder_date <=', date('Y-m-d', strtotime('+30 days')))
                    ->orderBy('reminder_date', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }
}
