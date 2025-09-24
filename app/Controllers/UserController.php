<?php
require_once __DIR__ . "/../Models/UserModel.php";
require_once __DIR__ . "/../Views/JsonView.php";

// Classe que controla o usuário. É responsável por fazer a ponte entre o model e a view
class UserController
{
  // Atributo do modelo (privado)
  private $userModel;

  // Método construtor. Cria o modelo do usuário
  public function __construct()
  {
    $this->userModel = new UserModel();
  }

  // Método POST que cria usuário
  public function create($data)
  {
    if (!$data->username || !$data->email || !$data->pass) {
      JsonView::render(["error" => "Campos obrigatórios faltando"], 400);
      return;
    }

    $result = $this->userModel->createUser(
      $data->username,
      $data->email,
      $data->pass,
      $data->phone ?? null
    );

    JsonView::render(["message" => $result]);
  }

  // Método que retorna todos os usuários da tabela
  public function index()
  {
    $result = $this->userModel->getAllUsers();
    return JsonView::render([$result], 200);
  }

  // Método que retorna o usuário pelo Id
  public function show($id)
  {
    $userData = $this->userModel->getUserById($id);
    if ($userData) {
      return JsonView::render([
        'id'        => $userData['id'],
        'username'  => $userData['username'],
        'email'     => $userData['email'],
        'phone'     => $userData['phone']
      ]);
    } else {
      return JsonView::render(["message" => "Usuário não encontrado"], 400);
    }
  }

  // Método que atualiza um usuário através do ID
  public function update($id, $data)
  {
    $msg = $this->userModel->updateUser($id, $data);
    JsonView::render(["message" => $msg], 200);
    return;
  }

  // Método que deleta um usuário do banco de dados
  public function delete($id)
  {
    $msg = $this->userModel->deleteUser($id);
    JsonView::render(["message" => $msg], 200);
  }

  // Método que autentifica o usuário
  public function auth($data)
  {
    // Verificando se os dados necessários foram passados na requisição
    if (!$data->email || !$data->pass) {
      JsonView::render(["error" => "Campos obrigatórios faltando"], 400);
      return;
    }

    try {
      // Adquirindo o usuário pelo email
      $user = $this->userModel->getUserByEmail($data->email);

      // Se a senha inserida bater com a senha do usuário, retorna um array associativo com os atributos do usuário
      if ($user && password_verify($data->pass, $user['pass_hash'])) {
        return [
          'id'    => $user['id'],
          'username'  => $user['username'],
          'email' => $user['email']
        ];
      }

      // Em caso da senha ser diferente, retorna o seguinte:
      JsonView::render(["message" => "Senha ou e-mail inválidos"], 400);
      return null;
      
    } catch (PDOException $e) {
      return JsonView::render(["error" => "Erro: " . $e->getMessage()], 500);
    }
  }
}
