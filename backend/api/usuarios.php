<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        listarUsuarios();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}

function listarUsuarios() {
    try {
        $pdo = getPDO();
        // Consulta adaptada a la tabla 'users'
        $stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users");
        $usuarios = $stmt->fetchAll();
        
        echo json_encode([
            'status' => 'success',
            'data' => $usuarios
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}
