<?php
require_once __DIR__ . "/../app/Core/Router.php";


// Configurando o CORS
$allowedOrigins = [
    'http://localhost:5173',
    // Se necessário, adicionarei mais domínios aqui
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}

header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Instancia a classe Router e chama a rota
$router = new Router();
$router->route($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
