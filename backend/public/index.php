<?php
// backend/public/index.php
// Acceso: /api/auth.php?action=register  etc.
// Simple redirect to files in ../api based on path
$path = $_SERVER['REQUEST_URI'];
// Asumiendo que abres mediante: php -S localhost:8000 -t public
// Llamadas directas a /api/auth.php?action=login funcionarán.
echo "API backend running. Llama a /api/auth.php o /api/servicios.php desde este host.";
