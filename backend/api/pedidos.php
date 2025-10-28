<?php
// backend/api/pedidos.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/jwt_helper.php';
$pdo = getPDO();
$method = $_SERVER['REQUEST_METHOD'];

function bearer_token(){
    $h = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
    if (!$h) return null;
    if (preg_match('/Bearer\s(\S+)/', $h, $m)) return $m[1];
    return null;
}
function json($d,$code=200){ http_response_code($code); echo json_encode($d); exit; }

$token = bearer_token();
$user = $token ? verify_jwt($token) : null;
if (!$user) json(['error'=>'No autorizado'],401);

if ($method === 'POST') {
    // crear pedido: service_id, notes
    $data = json_decode(file_get_contents('php://input'), true);
    $service_id = intval($data['service_id'] ?? 0);
    $notes = $data['notes'] ?? '';

    if (!$service_id) json(['error'=>'service_id requerido'],400);
    // fetch service
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $s = $stmt->fetch();
    if (!$s) json(['error'=>'Servicio no encontrado'],404);

    // prevent professors from offering services (they cannot be sellers anyway)
    $seller_id = $s['user_id'];
    $buyer_id = $user->id;

    // create order
    $stmt = $pdo->prepare("INSERT INTO orders (service_id,buyer_id,seller_id,notes) VALUES (?,?,?,?)");
    $stmt->execute([$service_id,$buyer_id,$seller_id,$notes]);
    json(['message'=>'Pedido creado','id'=>$pdo->lastInsertId()],201);
}

if ($method === 'GET') {
    // listar pedidos del usuario, con role
    // si ?all=1 y es admin (no implementado) podrías listar todo; ahora listamos por usuario (buyer or seller)
    $stmt = $pdo->prepare("SELECT o.*, s.title as service_title, u_s.name as seller_name, u_b.name as buyer_name
                           FROM orders o
                           JOIN services s ON o.service_id = s.id
                           JOIN users u_s ON o.seller_id = u_s.id
                           JOIN users u_b ON o.buyer_id = u_b.id
                           WHERE o.buyer_id = ? OR o.seller_id = ?
                           ORDER BY o.created_at DESC");
    $stmt->execute([$user->id,$user->id]);
    $rows = $stmt->fetchAll();
    json($rows);
}

if ($method === 'PUT') {
    // actualizar status (seller or buyer can change under rules)
    $data = json_decode(file_get_contents('php://input'), true);
    $id = intval($data['id'] ?? 0);
    $status = $data['status'] ?? null; // accepted, completed, cancelled
    if (!$id || !$status) json(['error'=>'id y status requeridos'],400);

    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$id]);
    $o = $stmt->fetch();
    if (!$o) json(['error'=>'Pedido no existe'],404);

    // only seller can accept or complete; buyer can cancel pending
    if ($status === 'accepted' || $status === 'completed') {
        if ($user->id != $o['seller_id']) json(['error'=>'Solo el vendedor puede aceptar/completar'],403);
    }
    if ($status === 'cancelled') {
        if ($user->id != $o['buyer_id']) json(['error'=>'Solo el comprador puede cancelar'],403);
    }
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status,$id]);
    json(['message'=>'Pedido actualizado']);
}
json(['error'=>'Método no soportado'],405);