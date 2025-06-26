<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GuestModel;
use App\Models\ServiceRequestModel;
class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/index');
        }

        return view('auth/login');
    }
    public function home()
    {
        $guestModel = new GuestModel();
        $ServiceModel = new ServiceRequestModel();
        $guests = $guestModel->findAll();  // For guest dropdown
        $requests = $ServiceModel->findAll();    // For assigned user dropdown
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to continue');
        }
        return view('auth/home', [
            'guests' => $guests,
            'requests' => $requests
        ]);
    }
    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Basic validation
        if (empty($username) || empty($password)) {
            return redirect()->back()->with('error', 'Username and password are required');
        }

        // Log the login attempt
        log_message('info', 'Login attempt for username: ' . $username);

        // Use the authenticate method instead of getUserForAuth
        $user = $this->userModel->authenticate($username, $password);

        if ($user) {
            // Authentication successful
            session()->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'email' => $user['email'],
                'isLoggedIn' => true
            ]);
            log_message('info', 'Login successful for user: ' . $username);
            return redirect()->to('/home')->with('success', 'Login successful');

        }
        log_message('warning', 'Login failed for username: ' . $username);
        return redirect()->back()->with('error', 'Invalid username or password');
    }

    public function signup()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/home');
        }

        return view('auth/signup');
    }

    public function register()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'role' => 'required|in_list[admin,staff]'
        ];

        $messages = [
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
            ],
            'confirm_password' => [
                'required' => 'Please confirm your password',
                'matches' => 'Passwords do not match'
            ],
            'role' => [
                'required' => 'Please select a role',
                'in_list' => 'Please select a valid role'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role')
        ];

        $result = $this->userModel->register($userData);

        if ($result['success']) {
            return redirect()->to('/login')->with('success', 'Registration successful! Please login.');
        } else {
            return redirect()->back()->withInput()->with('errors', $result['errors']);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logged out successfully');
    }
}