<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to access this page');
        }
        
        // Optional: Check for specific roles if arguments are provided
        if ($arguments && !empty($arguments)) {
            $userRole = session()->get('role');
            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/login')->with('error', 'You do not have permission to access this page');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}