<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHelper
{
    private $secretKey;
    private $algorithm = 'HS256';
    private $ttl;
    
    public function __construct()
    {
        $this->secretKey = getenv('JWT_SECRET_KEY') ?: 'your-secret-key';
        $this->ttl = getenv('JWT_TIME_TO_LIVE') ?: 3600;
    }
    
    public function generateToken($payload)
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + $this->ttl;
        
        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }
    
    public function validateToken($token)
    {
        try {
            JWT::decode($token, new Key($this->secretKey, $this->algorithm));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function decodeToken($token)
    {
        try {
            return JWT::decode($token, new Key($this->secretKey, $this->algorithm));
        } catch (\Exception $e) {
            return null;
        }
    }
    public function getPayload($token)
    {
        $decoded = $this->decodeToken($token);
        return $decoded ? (array) $decoded : null;
    }
}