<?php
require_once __DIR__ . '/../Helpers/JWT.php';
require_once __DIR__ . '/../Views/JsonView.php';

class AuthMiddleware
{
  public static function handle()
  {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
      JsonView::render(["success" => false, "error" => "Token não enviado"], 401);
      exit;
    }

    $token = str_replace("Bearer ", "", $headers['Authorization']);
    $payload = JWT::validate($token);

    // Verifica se contem um payload e se o mesmo contem um campo de ID
    if (!$payload || !$payload['id']) {
      JsonView::render(["success" => false, "error" => "Token inválido ou expirado"], 401);
      exit;
    }

    return $payload['id'];
  }
}
