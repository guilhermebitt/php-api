<?php
require_once __DIR__ . "/../Models/UserModel.php";
require_once __DIR__ . "/../Views/JsonView.php";
require_once __DIR__ . '/../Middlewares/AuthMiddleware.php';

// Classe que controla o usuário. É responsável por fazer a ponte entre o model e a view
class UserController
{
  // Atributos privados da classe
  private $userModel;

  // Método construtor. Cria o modelo do usuário
  public function __construct()
  {
    $this->userModel = new UserModel();
  }

  // Método que retorna todos os usuários da tabela
  public function index()
  {
    $result = $this->userModel->getAllUsers();
    return JsonView::render([$result], 200);
  }

  // Método que autentica o usuário
  public function auth()
  {
    $userId = AuthMiddleware::handle();

    return JsonView::render([
      "message" => "Usuário autenticado!",
      "userId"  => $userId,
      "success" => true
    ], 200);
  }

  // Método POST que cria usuário
  public function create($data)
  {
    if (!$data->username || !$data->email || !$data->pass) {
      JsonView::render(["success" => false, "error" => "Campos obrigatórios faltando"], 400);
      return;
    }

    $result = $this->userModel->createUser(
      $data->username,
      $data->email,
      $data->pass,
      $data->phone ?? null,
      $data->profilePic ?? null
    );

    JsonView::render(["message" => $result[0], "success" => $result[1]]);
  }

  // Método que retorna o usuário pelo Id
  public function show($id)
  {
    $result = $this->userModel->getUserById($id);
    if ($result[1]) {
      $userData = $result[0];
      return JsonView::render([
        'id'          => $userData['id'],
        'username'    => $userData['username'],
        'email'       => $userData['email'],
        'phone'       => $userData['phone'],
        'profile_pic' => $userData['profile_pic']
      ]);
    } else {
      return JsonView::render(["error" => $result[0], "success" => $result[1]], 400);
    }
  }

  // Método que atualiza um usuário através do ID
  public function update($id, $data)
  {
    $result = $this->userModel->updateUser($id, $data);
    JsonView::render(["message" => $result[0], "success" => $result[1]], 200);
    return;
  }

  // Método que deleta um usuário do banco de dados
  public function delete($id)
  {
    $result = $this->userModel->deleteUser($id);
    JsonView::render(["message" => $result[0], "success" => $result[1]], 200);
  }
}
