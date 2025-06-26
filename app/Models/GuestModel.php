<?php

namespace App\Models;

use CodeIgniter\Model;

class GuestModel extends Model
{
    protected $table = 'guests';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'first_name',
        'last_name', 
        'email',
        'phone',
        'room_number',
        'check_in_date',
        'check_out_date',
        'status'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'first_name' => 'required|max_length[50]',
        'last_name' => 'required|max_length[50]',
        'email' => 'required|valid_email',
        'phone' => 'permit_empty|max_length[20]',
        'room_number' => 'permit_empty|max_length[10]',
        'check_in_date' => 'permit_empty|valid_date',
        'check_out_date' => 'permit_empty|valid_date',
        'status' => 'permit_empty|in_list[checked_in,checked_out,reserved]'
    ];
    
    protected $validationMessages = [
        'first_name' => [
            'required' => 'First name is required',
            'max_length' => 'First name cannot exceed 50 characters'
        ],
        'last_name' => [
            'required' => 'Last name is required',
            'max_length' => 'Last name cannot exceed 50 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
        ]
    ];
    
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get guests with their service requests count
     */
    public function getGuestsWithRequests()
    {
        return $this->select('guests.*, COUNT(service_requests.id) as total_requests')
                    ->join('service_requests', 'service_requests.guest_id = guests.id', 'left')
                    ->groupBy('guests.id')
                    ->orderBy('guests.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get guest by ID with service requests
     */
    public function getGuestWithRequests($id)
    {
        $guest = $this->find($id);
        
        if (!$guest) {
            return null;
        }

        // Get service requests for this guest
        $requestModel = new ServiceRequestModel();
        $guest['service_requests'] = $requestModel->where('guest_id', $id)->findAll();
        
        return $guest;
    }

    /**
     * Get guests by status
     */
    public function getGuestsByStatus($status)
    {
        return $this->where('status', $status)->findAll();
    }

    /**
     * Get checked-in guests
     */
    public function getCheckedInGuests()
    {
        return $this->getGuestsByStatus('checked_in');
    }

    /**
     * Get guests checking out today
     */
    public function getGuestsCheckingOutToday()
    {
        return $this->where('check_out_date', date('Y-m-d'))
                    ->where('status', 'checked_in')
                    ->findAll();
    }

    /**
     * Get guests checking in today
     */
    public function getGuestsCheckingInToday()
    {
        return $this->where('check_in_date', date('Y-m-d'))
                    ->where('status', 'reserved')
                    ->findAll();
    }

    /**
     * Search guests by name or email
     */
    public function searchGuests($term)
    {
        return $this->groupStart()
                    ->like('first_name', $term)
                    ->orLike('last_name', $term)
                    ->orLike('email', $term)
                    ->orLike('room_number', $term)
                    ->groupEnd()
                    ->findAll();
    }
    public static function getGuestName($id)
    {
        $model = new GuestModel(); // Instantiate the model

        $user = $model->where(['id' => $id])->first(); // Use 'first()' instead of 'one()'

        return $user ? $user['first_name'] . ' ' . $user['last_name'] : 'Unknown Guest';
    }
    public static function getGuestRoom($id)
    {
        $model = new GuestModel(); // Instantiate the model
        $user = $model->where(['id' => $id])->first(); // Use 'first()' instead of 'one()'
        return $user ? $user['room_number'] : 'Unknown Room';
    }
    /**
     * Get guest statistics
     */
    public function getStatistics()
    {
        $stats = [];
        
        $stats['total'] = $this->countAll();
        $stats['checked_in'] = $this->where('status', 'checked_in')->countAllResults();
        $stats['checked_out'] = $this->where('status', 'checked_out')->countAllResults();
        $stats['reserved'] = $this->where('status', 'reserved')->countAllResults();
        $stats['checking_in_today'] = $this->where('check_in_date', date('Y-m-d'))->countAllResults();
        $stats['checking_out_today'] = $this->where('check_out_date', date('Y-m-d'))->countAllResults();
        
        return $stats;
    }
}
