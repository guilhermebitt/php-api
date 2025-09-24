<?php
require_once __DIR__ . "/../app/Core/Router.php";

// Instancia a classe Router e chama a rota
$router = new Router();
$router->route($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);