<?php
require_once __DIR__ . '/../Helpers/JWT.php';
require_once __DIR__ . '/../Views/JsonView.php';

class AuthMiddleware
{
  public static function handle()
  {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
      JsonView::render(["error" => "Token não enviado"], 401);
      exit;
    }

    $token = str_replace("Bearer ", "", $headers['Authorization']);
    $payload = JWT::validate($token);

    if (!$payload) {
      JsonView::render(["error" => "Token inválido ou expirado"], 401);
      exit;
    }

    return $payload;
  }
}
