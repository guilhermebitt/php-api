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

  // Método que realiza o login do usuário
  public function login($data)
  {
    $result = $this->userModel->getUserByEmail($data->email ?? '');

    // Verifica se encontrou usuário
    if ($result[1]) {
      $user = $result[0];
    } else {
      return JsonView::render(["error" => "Usuário não encontrado", "success" => false], 401);
    }
    if (!password_verify($data->pass, $user['pass_hash'] ?? '')) {
      return JsonView::render(["error" => "Credenciais inválidas", "success" => false], 401);
    }

    $token = JWT::generate([
      "id" => $user['id']
    ], 36000);

    return JsonView::render(["token" => $token]);
  }
}
