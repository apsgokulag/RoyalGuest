<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'username',
        'email',
        'password',
        'role'
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
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role' => 'permit_empty|in_list[admin,staff]'
    ];
    
    protected $validationMessages = [
        'username' => [
            'required' => 'Username is required',
            'min_length' => 'Username must be at least 3 characters long',
            'max_length' => 'Username cannot exceed 50 characters',
            'is_unique' => 'This username is already taken'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'This email is already registered'
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 6 characters long'
        ]
    ];
    
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks - Remove password from normal results but keep for auth
    protected $allowCallbacks = true;
    protected $beforeInsert = ['hashPasswordCallback'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['hashPasswordCallback'];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = ['removePasswordCallback'];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Hash password before insert/update
     */
    protected function hashPasswordCallback(array $data)
    {
        // Check if we have password data to hash
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            // Only hash if it's not already hashed (doesn't start with $2y$)
            if (substr($data['data']['password'], 0, 4) !== '$2y$') {
                $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
                log_message('debug', 'Password hashed in callback');
            }
        }
        
        return $data;
    }

    /**
     * Remove password from result (except when specifically needed)
     */
    protected function removePasswordCallback(array $data)
    {
        if (isset($data['data'])) {
            if (is_array($data['data']) && isset($data['data']['password'])) {
                unset($data['data']['password']);
            } elseif (is_array($data['data'])) {
                foreach ($data['data'] as &$row) {
                    if (is_array($row) && isset($row['password'])) {
                        unset($row['password']);
                    }
                }
            }
        }
        
        return $data;
    }

    /**
     * Get user with password (bypassing callbacks completely) - FIXED VERSION
     */
    private function getUserWithPassword($username)
    {
        try {
            // Use direct database query to completely bypass model callbacks
            $db = \Config\Database::connect();
            
            // Try to find user by username first
            $query = $db->query("SELECT id, username, email, role, password FROM users WHERE username = ? LIMIT 1", [$username]);
            $user = $query->getRowArray();
            
            // If not found by username, try by email
            if (!$user) {
                $query = $db->query("SELECT id, username, email, role, password FROM users WHERE email = ? LIMIT 1", [$username]);
                $user = $query->getRowArray();
            }
            
            log_message('debug', 'Raw user data from direct query: ' . print_r($user, true));
            
            return $user;
            
        } catch (\Exception $e) {
            log_message('error', 'Error fetching user with password: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Primary authentication method - COMPLETELY FIXED VERSION
     */
    public function authenticate($username, $password)
    {
        log_message('debug', '=== AUTHENTICATION START ===');
        log_message('debug', 'Attempting to authenticate user: ' . $username);
        log_message('debug', 'Input password: ' . $password);
        
        try {
            // Get user with password (using direct database query)
            $user = $this->getUserWithPassword($username);
            
            if (!$user) {
                log_message('debug', 'User not found: ' . $username);
                return false;
            }
            
            log_message('debug', 'User found - ID: ' . $user['id'] . ', Username: ' . $user['username']);
            
            // Check if password field exists and is not empty
            if (!isset($user['password']) || empty($user['password'])) {
                log_message('error', 'Password field is missing or empty for user: ' . $username);
                log_message('debug', 'User data keys: ' . implode(', ', array_keys($user)));
                return false;
            }
            
            log_message('debug', 'Password field exists');
            log_message('debug', 'Password length: ' . strlen($user['password']));
            log_message('debug', 'Password hash preview: ' . substr($user['password'], 0, 20) . '...');
            log_message('debug', 'Password starts with $2y$: ' . (substr($user['password'], 0, 4) === '$2y$' ? 'YES' : 'NO'));
            
            // If password doesn't look hashed, it might be plain text - check both ways
            if (substr($user['password'], 0, 4) !== '$2y$') {
                log_message('warning', 'Password appears to be unhashed for user: ' . $username);
                
                // Check if it's a plain text match first
                if ($password === $user['password']) {
                    log_message('warning', 'Plain text password match - should hash this password!');
                    // Remove password before returning
                    unset($user['password']);
                    return $user;
                }
                
                // Try to hash the stored password and compare
                $hashedStored = password_hash($user['password'], PASSWORD_DEFAULT);
                if (password_verify($password, $hashedStored)) {
                    log_message('warning', 'Password matched after hashing stored password');
                    unset($user['password']);
                    return $user;
                }
            }
            
            // Standard password verification for hashed passwords
            $isValid = password_verify($password, $user['password']);
            log_message('debug', 'Password verification result: ' . ($isValid ? 'SUCCESS' : 'FAILED'));
            
            if ($isValid) {
                // Remove password before returning
                unset($user['password']);
                log_message('info', 'Authentication successful for user: ' . $username);
                return $user;
            } else {
                log_message('warning', 'Password verification failed for user: ' . $username);
                
                // Additional debugging - try direct comparison
                log_message('debug', 'Direct password comparison: ' . ($password === $user['password'] ? 'MATCH' : 'NO MATCH'));
                
                return false;
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Authentication error: ' . $e->getMessage());
            log_message('error', 'Authentication stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Get user for authentication - SIMPLIFIED VERSION
     */
    public function getUserForAuth($username)
    {
        return $this->getUserWithPassword($username);
    }

    /**
     * Register a new user - IMPROVED VERSION
     */
    public function register(array $userData)
    {
        try {
            log_message('debug', 'Starting user registration for: ' . $userData['username']);
            
            // Hash password before validation
            if (isset($userData['password'])) {
                $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
                log_message('debug', 'Password hashed for registration');
            }

            // Temporarily disable callbacks and password validation
            $originalCallbacks = $this->allowCallbacks;
            $originalRules = $this->validationRules;
            
            $this->allowCallbacks = false;
            unset($this->validationRules['password']); // Skip password validation since it's hashed
            
            // Validate other fields
            if (!$this->validate($userData)) {
                $this->allowCallbacks = $originalCallbacks;
                $this->validationRules = $originalRules;
                
                return [
                    'success' => false,
                    'errors' => $this->errors()
                ];
            }

            // Insert user
            $userId = $this->insert($userData, false); // Skip validation on insert
            
            // Restore settings
            $this->allowCallbacks = $originalCallbacks;
            $this->validationRules = $originalRules;
            
            if ($userId) {
                log_message('info', 'User registered successfully: ' . $userData['username']);
                return [
                    'success' => true,
                    'user_id' => $userId,
                    'message' => 'User registered successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'errors' => ['registration' => 'Failed to register user']
                ];
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Registration error: ' . $e->getMessage());
            return [
                'success' => false,
                'errors' => ['registration' => $e->getMessage()]
            ];
        }
    }

    /**
     * Get users with service requests count
     */
    public function getUsersWithRequestsCount()
    {
        return $this->select('users.*, COUNT(service_requests.id) as assigned_requests')
                    ->join('service_requests', 'service_requests.assigned_to = users.id', 'left')
                    ->groupBy('users.id')
                    ->orderBy('users.username', 'ASC')
                    ->findAll();
    }

    /**
     * Get users by role
     */
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)->findAll();
    }

    /**
     * Get all admin users
     */
    public function getAdminUsers()
    {
        return $this->getUsersByRole('admin');
    }

    /**
     * Get all staff users
     */
    public function getStaffUsers()
    {
        return $this->getUsersByRole('staff');
    }

    /**
     * Update user password
     */
    public function updatePassword($userId, $newPassword)
    {
        return $this->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }

    /**
     * Check if user exists by username
     */
    public function userExistsByUsername($username)
    {
        return $this->where('username', $username)->countAllResults() > 0;
    }

    /**
     * Check if user exists by email
     */
    public function userExistsByEmail($email)
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }

    /**
     * Get user statistics
     */
    public function getStatistics()
    {
        $stats = [];
        
        $stats['total'] = $this->countAll();
        $stats['admin'] = $this->where('role', 'admin')->countAllResults();
        $stats['staff'] = $this->where('role', 'staff')->countAllResults();
        $stats['recent'] = $this->where('created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))->countAllResults();
        
        return $stats;
    }

    /**
     * Search users
     */
    public function searchUsers($term)
    {
        return $this->groupStart()
                    ->like('username', $term)
                    ->orLike('email', $term)
                    ->groupEnd()
                    ->findAll();
    }

    /**
     * Utility method to fix unhashed passwords in database
     */
    public function fixUnhashedPasswords()
    {
        $originalCallbacks = $this->allowCallbacks;
        $this->allowCallbacks = false;
        
        try {
            // Get all users with their passwords using direct query
            $db = \Config\Database::connect();
            $query = $db->query("SELECT id, username, password FROM users");
            $users = $query->getResultArray();
            
            $fixed = 0;
            
            foreach ($users as $user) {
                // Check if password looks unhashed (not starting with $2y$)
                if (isset($user['password']) && substr($user['password'], 0, 4) !== '$2y$') {
                    // Hash the password
                    $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
                    
                    // Update user directly
                    $updateQuery = $db->query("UPDATE users SET password = ? WHERE id = ?", [$hashedPassword, $user['id']]);
                    $fixed++;
                    
                    log_message('info', 'Fixed password for user: ' . $user['username']);
                }
            }
            
            return [
                'success' => true,
                'fixed_count' => $fixed,
                'message' => "Fixed {$fixed} unhashed passwords"
            ];
            
        } catch (\Exception $e) {
            log_message('error', 'Error fixing unhashed passwords: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        } finally {
            $this->allowCallbacks = $originalCallbacks;
        }
    }

    /**
     * Enhanced test authentication method for debugging
     */
    public function testAuth($username, $password)
    {
        log_message('debug', '=== TESTING AUTHENTICATION ===');
        log_message('debug', 'Testing username: ' . $username);
        log_message('debug', 'Testing password: ' . $password);
        
        // Test direct database query
        try {
            $db = \Config\Database::connect();
            $query = $db->query("SELECT id, username, email, role, password FROM users WHERE username = ? OR email = ?", [$username, $username]);
            $user = $query->getRowArray();
            
            if (!$user) {
                return [
                    'success' => false, 
                    'message' => 'User not found in database',
                    'query_executed' => true
                ];
            }
            
            $result = [
                'success' => true,
                'user_id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'],
                'password_exists' => isset($user['password']) && !empty($user['password']),
                'password_length' => isset($user['password']) ? strlen($user['password']) : 0,
                'password_preview' => isset($user['password']) ? substr($user['password'], 0, 20) . '...' : 'N/A',
                'password_is_hashed' => isset($user['password']) ? (substr($user['password'], 0, 4) === '$2y$') : false,
                'password_verify_result' => false,
                'direct_match' => false
            ];
            
            if (isset($user['password']) && !empty($user['password'])) {
                // Test password verification
                $result['password_verify_result'] = password_verify($password, $user['password']);
                $result['direct_match'] = ($password === $user['password']);
                
                // If not hashed, test plain text
                if (substr($user['password'], 0, 4) !== '$2y$') {
                    $result['plain_text_match'] = ($password === $user['password']);
                }
            }
            
            log_message('debug', 'TEST RESULT: ' . print_r($result, true));
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'Test authentication error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'error' => true
            ];
        }
    }

    /**
     * Quick method to check and display user data
     */
    public function debugUser($username)
    {
        try {
            $db = \Config\Database::connect();
            $query = $db->query("SELECT * FROM users WHERE username = ? OR email = ?", [$username, $username]);
            $user = $query->getRowArray();
            
            if ($user) {
                // Don't log the full password, just show it exists and format
                $user['password_debug'] = [
                    'exists' => isset($user['password']) && !empty($user['password']),
                    'length' => isset($user['password']) ? strlen($user['password']) : 0,
                    'starts_with' => isset($user['password']) ? substr($user['password'], 0, 10) : 'N/A',
                    'is_hashed' => isset($user['password']) ? (substr($user['password'], 0, 4) === '$2y$') : false
                ];
                unset($user['password']); // Remove actual password from debug output
            }
            
            return $user;
            
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}