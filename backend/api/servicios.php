<?php
// backend/api/servicios.php
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

if ($method === 'GET') {
    // list services with optional filters
    $q = $_GET['q'] ?? null;
    $category = $_GET['category'] ?? null;

    $sql = "SELECT s.*, u.name as seller_name, u.email as seller_email FROM services s JOIN users u ON s.user_id = u.id WHERE s.is_active=1";
    $params = [];
    if ($category) { $sql .= " AND s.category = ?"; $params[] = $category; }
    if ($q) { $sql .= " AND (s.title LIKE ? OR s.description LIKE ?)"; $params[] = "%$q%"; $params[] = "%$q%"; }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();
    json($rows);
}

if ($method === 'POST') {
    if (!$user) json(['error'=>'No autorizado'],401);
    // only students can create services
    if ($user->role !== 'student') json(['error'=>'Solo estudiantes pueden ofrecer servicios'],403);

    $data = json_decode(file_get_contents('php://input'), true);
    $title = $data['title'] ?? '';
    $category = $data['category'] ?? 'general';
    $description = $data['description'] ?? '';
    $price = floatval($data['price'] ?? 0.0);

    if (!$title) json(['error'=>'Título requerido'],400);

    $stmt = $pdo->prepare("INSERT INTO services (user_id,title,category,description,price) VALUES (?,?,?,?,?)");
    $stmt->execute([$user->id,$title,$category,$description,$price]);
    json(['message'=>'Servicio creado','id'=>$pdo->lastInsertId()],201);
}

if ($method === 'PUT') {
    if (!$user) json(['error'=>'No autorizado'],401);
    $data = json_decode(file_get_contents('php://input'), true);
    $id = intval($data['id'] ?? 0);
    if (!$id) json(['error'=>'Id requerido'],400);

    // check owner
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $s = $stmt->fetch();
    if (!$s) json(['error'=>'No existe servicio'],404);
    if ($s['user_id'] != $user->id) json(['error'=>'No eres dueño del servicio'],403);

    $fields = [];
    $params = [];
    foreach (['title','category','description','price','is_active'] as $f){
        if (isset($data[$f])) { $fields[] = "$f = ?"; $params[] = $data[$f]; }
    }
    if (empty($fields)) json(['error'=>'Nada para actualizar'],400);
    $params[] = $id;
    $sql = "UPDATE services SET " . implode(',', $fields) . " WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    json(['message'=>'Servicio actualizado']);
}

if ($method === 'DELETE') {
    if (!$user) json(['error'=>'No autorizado'],401);
    parse_str(file_get_contents("php://input"), $delData);
    $id = intval($delData['id'] ?? 0);
    if (!$id) json(['error'=>'Id requerido'],400);
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $s = $stmt->fetch();
    if (!$s) json(['error'=>'No existe servicio'],404);
    if ($s['user_id'] != $user->id) json(['error'=>'No eres dueño del servicio'],403);
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);
    json(['message'=>'Servicio eliminado']);
}
json(['error'=>'Método no soportado'],405);