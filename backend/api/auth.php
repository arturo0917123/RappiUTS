<?php
// backend/api/auth.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/jwt_helper.php';

$pdo = getPDO();
$method = $_SERVER['REQUEST_METHOD'];

function json($d, $code=200){ http_response_code($code); echo json_encode($d); exit; }

if ($method === 'POST') {
    $path = $_GET['action'] ?? '';
    $data = json_decode(file_get_contents('php://input'), true);

    if ($path === 'register') {
        // required: name, email, password
        $name = trim($data['name'] ?? '');
        $email = strtolower(trim($data['email'] ?? ''));
        $password = $data['password'] ?? '';

        if (!$name || !$email || !$password) json(['error'=>'Campos faltantes'],400);

        // domain validation
        if (str_ends_with($email, '@uts.edu.co')) $role = 'student';
        elseif (str_ends_with($email, '@correo.uts.edu.co')) $role = 'professor';
        else json(['error'=>'Correo no institucional permitido. Usa @uts.edu.co o @correo.uts.edu.co'],400);

        // check exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) json(['error'=>'Email ya registrado'],409);

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,?)");
        $stmt->execute([$name,$email,$hash,$role]);
        $id = $pdo->lastInsertId();

        $token = create_jwt(['id'=>$id,'email'=>$email,'role'=>$role]);
        json(['message'=>'Registrado','token'=>$token,'role'=>$role]);
    }

    if ($path === 'login') {
        $email = strtolower(trim($data['email'] ?? ''));
        $password = $data['password'] ?? '';
        if (!$email || !$password) json(['error'=>'Campos faltantes'],400);

        $stmt = $pdo->prepare("SELECT id,email,password_hash,role,name FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (!$user) json(['error'=>'Credenciales inválidas'],401);
        if (!password_verify($password, $user['password_hash'])) json(['error'=>'Credenciales inválidas'],401);

        $token = create_jwt(['id'=>$user['id'],'email'=>$user['email'],'role'=>$user['role'],'name'=>$user['name']]);
        json(['message'=>'Login exitoso','token'=>$token,'role'=>$user['role']]);
    }
}

json(['error'=>'Método no soportado'],405);
?>