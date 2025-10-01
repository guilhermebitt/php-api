<?php
require_once __DIR__ . "/../Models/UserModel.php";
require_once __DIR__ . "/../Views/JsonView.php";

// Classe que controla a autentificação. É responsável por fazer a ponte entre o model e a view
class AuthController
{
  // Atributo do modelo (privado)
  private $userModel;

  // Método construtor. Cria o modelo do usuário
  public function __construct()
  {
    $this->userModel = new UserModel();
  }

  public function login($data)
  {
    // Retorna um erro se não receber a data completa
    if (!$data->email || !$data->pass) {
      return JsonView::render(["error" => "Email e senha obrigatórios"], 400);
    }

    // Procura por um usuário com o email correspondente no banco de dados
    $userData = $this->userModel->getUserByEmail($data->email);

    // Verifica se há um usuário e se sua senha está correta
    if ($userData && password_verify($data->pass, $userData["pass_hash"])) {
      return JsonView::render([
        "success" => true,
        "user" => [
          'id'        => $userData['id'],
          'username'  => $userData['username'],
          'email'     => $userData['email'],
          'phone'     => $userData['phone']
        ]
      ]);
    } else {
      return JsonView::render(["success" => false]);
    }
  }
}
