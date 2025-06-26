<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceRequestModel extends Model
{
    protected $table = 'service_requests';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'guest_id',
        'service_type',
        'description',
        'priority',
        'status',
        'assigned_to'
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
        'guest_id' => 'required|integer|is_not_unique[guests.id]',
        'service_type' => 'required|max_length[100]',
        'description' => 'permit_empty|string',
        'priority' => 'permit_empty|in_list[low,medium,high]',
        'status' => 'permit_empty|in_list[pending,in_progress,completed,cancelled]',
        'assigned_to' => 'permit_empty|integer|is_not_unique[users.id]'
    ];
    
    protected $validationMessages = [
        'guest_id' => [
            'required' => 'Guest is required',
            'integer' => 'Guest ID must be a valid number',
            'is_not_unique' => 'Selected guest does not exist'
        ],
        'service_type' => [
            'required' => 'Service type is required',
            'max_length' => 'Service type cannot exceed 100 characters'
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
     * Get service requests with guest information
     */
    public function getRequestsWithGuests()
    {
        return $this->select('service_requests.*, 
                             guests.first_name, 
                             guests.last_name, 
                             guests.email, 
                             guests.room_number,
                             users.username as assigned_user')
                    ->join('guests', 'guests.id = service_requests.guest_id', 'left')
                    ->join('users', 'users.id = service_requests.assigned_to', 'left')
                    ->orderBy('service_requests.created_at', 'DESC');
    }

    /**
     * Get service request with guest information
     */
    public function getRequestWithGuest($id)
    {
        return $this->select('service_requests.*, 
                             guests.first_name, 
                             guests.last_name, 
                             guests.email, 
                             guests.room_number,
                             users.username as assigned_user')
                    ->join('guests', 'guests.id = service_requests.guest_id', 'left')
                    ->join('users', 'users.id = service_requests.assigned_to', 'left')
                    ->where('service_requests.id', $id)
                    ->first();
    }

    /**
     * Get requests by status
     */
    public function getRequestsByStatus($status)
    {
        return $this->getRequestsWithGuests()
                    ->where('service_requests.status', $status);
    }

    /**
     * Get pending requests
     */
    public function getPendingRequests()
    {
        return $this->where('status', 'pending')
                    ->orderBy('priority', 'DESC')
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get requests by priority
     */
    public function getRequestsByPriority($priority)
    {
        return $this->getRequestsWithGuests()
                    ->where('service_requests.priority', $priority);
    }

    /**
     * Get requests assigned to user
     */
    public function getRequestsAssignedTo($userId)
    {
        return $this->getRequestsWithGuests()
                    ->where('service_requests.assigned_to', $userId);
    }

    /**
     * Get requests for a specific guest
     */
    public function getRequestsForGuest($guestId)
    {
        return $this->where('guest_id', $guestId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
    public static function getService($id)
    {
        $model = new ServiceRequestModel(); // Instantiate the model
        $user = $model->where(['id' => $id])->first(); // Use 'first()' instead of 'one()'
        return $user ? $user['service'] : 'Unknown Service';
    }
    public static function getServiceStatus($id)
    {
        $model = new ServiceRequestModel(); // Instantiate the model
        $user = $model->where(['id' => $id])->first(); // Use 'first()' instead of 'one()'
        return $user ? $user['status'] : 'Unknown status';
    }
    /**
     * Get recent requests (last 24 hours)
     */
    public function getRecentRequests($limit = 10)
    {
        return $this->getRequestsWithGuests()
                    ->where('service_requests.created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
                    ->limit($limit);
    }

    /**
     * Get requests statistics
     */
    public function getStatistics()
    {
        $stats = [];
        
        $stats['total'] = $this->countAll();
        $stats['pending'] = $this->where('status', 'pending')->countAllResults();
        $stats['in_progress'] = $this->where('status', 'in_progress')->countAllResults();
        $stats['completed'] = $this->where('status', 'completed')->countAllResults();
        $stats['cancelled'] = $this->where('status', 'cancelled')->countAllResults();
        
        // Priority statistics
        $stats['high_priority'] = $this->where('priority', 'high')->countAllResults();
        $stats['medium_priority'] = $this->where('priority', 'medium')->countAllResults();
        $stats['low_priority'] = $this->where('priority', 'low')->countAllResults();
        
        // Today's requests
        $stats['today'] = $this->where('DATE(created_at)', date('Y-m-d'))->countAllResults();
        
        return $stats;
    }

    /**
     * Update request status
     */
    public function updateRequestStatus($id, $status, $assignedTo = null)
    {
        $data = ['status' => $status];
        
        if ($assignedTo !== null) {
            $data['assigned_to'] = $assignedTo;
        }
        
        return $this->update($id, $data);
    }

    /**
     * Assign request to user
     */
    public function assignRequest($id, $userId)
    {
        return $this->update($id, ['assigned_to' => $userId]);
    }

    /**
     * Get requests by service type
     */
    public function getRequestsByServiceType($serviceType)
    {
        return $this->getRequestsWithGuests()
                    ->like('service_requests.service_type', $serviceType);
    }

    /**
     * Search requests
     */
    public function searchRequests($term)
    {
        return $this->getRequestsWithGuests()
                    ->groupStart()
                    ->like('service_requests.service_type', $term)
                    ->orLike('service_requests.description', $term)
                    ->orLike('guests.first_name', $term)
                    ->orLike('guests.last_name', $term)
                    ->orLike('guests.room_number', $term)
                    ->groupEnd();
    }

    /**
     * Get overdue requests (pending for more than 24 hours)
     */
    public function getOverdueRequests()
    {
        return $this->getRequestsWithGuests()
                    ->where('service_requests.status', 'pending')
                    ->where('service_requests.created_at <', date('Y-m-d H:i:s', strtotime('-24 hours')));
    }
}