<?php
require_once __DIR__ . "/../Controllers/UserController.php";
require_once __DIR__ . "/../Controllers/LoginController.php";

// Classe de controle de rota do sistema MVC
class Router
{
  // Atributo privado para o controle de usuário
  private $userController;
  private $loginController;

  // Método construtor que instância o controller
  public function __construct()
  {
    $this->userController = new UserController();
    $this->loginController = new LoginController();
  }

  // Método público que retorna um código com base na rota inserida pelo cliente
  public function route($uri, $method)
  {
    // Remove query string da URI, se houver
    $uri = parse_url($uri, PHP_URL_PATH);

    // Separa as partes da uri por "/"
    $parts = explode("/", trim($uri, "/"));

    // Adquire a data da requisição
    $data = json_decode(file_get_contents("php://input")) ?? null;

    // Se a primeira parte for "crud", remove ela antes de continuar o código
    if ($parts[0] == 'crud') {
      array_shift($parts);
    }

    // Se não receber um endpoint, retorna um erro
    if (!$parts) {
      http_response_code(400);
      echo json_encode(["error" => "Nenhum endpoint selecionado"]);
      return;
    }

    // AUTENTIFICAÇÃO
    if ($parts[0] == "auth") {
      $action = $parts[1] ?? null;

      switch ($method) {
        case "POST": // Gera o token para o usuário
          $this->loginController->login($data);
          break;
        
        case "GET": // Valida o token e retorna os dados do usuário
          $this->userController->auth();
      }
    }

    // CRUD USUÁRIO
    if ($parts[0] == "users") {
      $id = $parts[1] ?? null;  // se tiver ID na rota

      switch ($method) {
        case "POST": // Criar usuário
          $this->userController->create($data);
          break;

        case "GET":
          if ($id) {
            // GET /users/{id} -> mostra um usuário
            $this->userController->show($id);
          } else {
            // GET /users -> lista usuários
            $this->userController->index();
          }
          break;

        case "PUT":
        case "PATCH":
          if ($id) {
            $this->userController->update($id, $data);
          } else {
            http_response_code(400);
            echo json_encode(["error" => "ID obrigatório para atualização"]);
          }
          break;

        case "DELETE":
          if ($id) {
            $this->userController->delete($id);
          } else {
            http_response_code(400);
            echo json_encode(["error" => "ID obrigatório para exclusão"]);
          }
          break;

        default:
          http_response_code(405);
          echo json_encode(["error" => "Método não permitido"]);
          break;
      }
    }
  }
}
