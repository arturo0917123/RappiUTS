<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Obtenemos la ruta solicitada
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Si comienza con /api/, intentamos cargar el archivo correspondiente
if (strpos($request, '/api/') === 0) {
    $path = __DIR__ . '/../' . ltrim($request, '/');

    if (file_exists($path)) {
        require $path;
        exit;
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada", "path" => $path]);
        exit;
    }
}

// Ruta raíz
echo json_encode(["message" => "Servidor backend UTS funcionando correctamente 🚀"]);
