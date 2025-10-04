<?php
require_once __DIR__ . '/../Models/UserModel.php';
require_once __DIR__ . '/../Views/JsonView.php';
require_once __DIR__ . '/../Helpers/JWT.php';

class LoginController
{
  // Atributos privados da classe
  private $userModel;

  // Método construtor. Cria o modelo do usuário
  public function __construct()
  {
    $this->userModel = new UserModel();
  }

  public function login($data)
  {
    $user = $this->userModel->getUserByEmail($data->email ?? '');
    if (!$user || !password_verify($data->pass, $user['pass_hash'] ?? '')) {
      return JsonView::render(["error" => "Credenciais inválidas"], 401);
    }

    $token = JWT::generate([
      "id"        => $user['id'],
      "username"  => $user['username'],
      "email"     => $user['email'],
      "phone"     => $user['phone']
    ], 36000);

    return JsonView::render(["token" => $token]);
  }
}
