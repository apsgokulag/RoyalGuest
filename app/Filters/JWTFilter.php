<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\JWTHelper;

class JWTFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $jwtHelper = new JWTHelper();
        $authHeader = $request->getHeaderLine('Authorization');
        
        if (empty($authHeader)) {
            return service('response')->setJSON([
                'status' => false,
                'message' => 'Authorization header not found'
            ])->setStatusCode(401);
        }
        
        $token = str_replace('Bearer ', '', $authHeader);
        
        if (!$jwtHelper->validateToken($token)) {
            return service('response')->setJSON([
                'status' => false,
                'message' => 'Invalid or expired token'
            ])->setStatusCode(401);
        }
        
        $payload = $jwtHelper->decodeToken($token);
        $request->user = $payload;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}