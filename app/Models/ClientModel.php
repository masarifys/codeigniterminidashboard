<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'business_name', 'contact_person', 'contact_email', 'contact_phone', 'domain', 'status', 'notes'];

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
        'business_name' => 'required|min_length[3]|max_length[255]',
        'contact_email' => 'permit_empty|valid_email',
        'status' => 'permit_empty|in_list[progress,revision,completed,cancelled]',
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

    public function getClientWithUser($clientId)
    {
        return $this->select('clients.*, users.username, users.email as user_email, users.full_name')
                    ->join('users', 'users.id = clients.user_id')
                    ->where('clients.id', $clientId)
                    ->first();
    }

    public function getClientsWithUser()
    {
        return $this->select('clients.*, users.username, users.email as user_email, users.full_name')
                    ->join('users', 'users.id = clients.user_id')
                    ->orderBy('clients.created_at', 'DESC')
                    ->findAll();
    }
}
