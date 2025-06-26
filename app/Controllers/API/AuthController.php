<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Libraries\JWTHelper;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $userModel;
    protected $jwtHelper;
    public function login()
    {
        $userModel = new UserModel();
        $jwtHelper = new JWTHelper();
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        if (empty($username) || empty($password)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Username and password are required'
            ])->setStatusCode(400);
        }
        
        $user = $userModel->authenticate($username, $password);
        
        if ($user) {
            $token = $jwtHelper->generateToken([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ]);
            
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ]);
        }
        
        return $this->response->setJSON([
            'status' => false,
            'message' => 'Invalid credentials'
        ])->setStatusCode(401);
    }
    public function register()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role' => 'permit_empty|in_list[admin,staff]'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }
        
        $userData = [
            'username' => $this->request->getJSON()->username,
            'email' => $this->request->getJSON()->email,
            'password' => password_hash($this->request->getJSON()->password, PASSWORD_DEFAULT),
            'role' => $this->request->getJSON()->role ?? 'staff'
        ];
        
        if ($this->userModel->insert($userData)) {
            $userId = $this->userModel->getInsertID();
            $user = $this->userModel->find($userId);
            
            // Generate JWT token
            $token = $this->jwtHelper->generateToken([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ]);
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => [
                    'user' => [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'role' => $user['role']
                    ],
                    'token' => $token
                ]
            ])->setStatusCode(ResponseInterface::HTTP_CREATED);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Registration failed'
        ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function me()
    {
        $token = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $token);
        
        if (!$token) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Token not provided'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
        
        $payload = $this->jwtHelper->validateToken($token);
        
        if (!$payload) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid token'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
        
        $user = $this->userModel->find($payload['user_id']);
        
        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ]
        ]);
    }
}