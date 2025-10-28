<?php
// backend/helpers/jwt_helper.php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

require_once __DIR__ . '/../vendor/autoload.php';

function create_jwt($payload) {
    $secret = $_ENV['JWT_SECRET'];
    $issuedAt   = time();
    $expire = $issuedAt + intval($_ENV['JWT_EXPIRE'] ?? 3600);
    $token = [
        'iat' => $issuedAt,
        'exp' => $expire,
        'data' => $payload
    ];
    return JWT::encode($token, $secret, 'HS256');
}

function verify_jwt($token) {
    $secret = $_ENV['JWT_SECRET'];
    try {
        $decoded = JWT::decode($token, new Key($secret, 'HS256'));
        return $decoded->data ?? null;
    } catch (Exception $e) {
        return null;
    }
}
?>