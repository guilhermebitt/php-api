<?php
require_once __DIR__ . "/../Controllers/UserController.php";
require_once __DIR__ . "/../Controllers/AuthController.php";

// Classe de controle de rota do sistema MVC
class Router
{
  // Atributo privado para o controle de usuário
  private $userController;
  private $authController;

  // Método construtor que instância o controller
  public function __construct()
  {
    $this->userController = new UserController();
    $this->authController = new AuthController();
  }

  // Método público que retorna um código com base na rota inserida pelo cliente
  public function route($uri, $method)
  {
    // Remove query string da URI, se houver
    $uri = parse_url($uri, PHP_URL_PATH);

    // Separa as partes da uri por "/"
    $parts = explode("/", trim($uri, "/"));

    // Se a primeira parte for "crud", remove ela antes de continuar o código
    if ($parts[0] == 'crud') {
      array_shift($parts);
    }

    // LOGIN
    if ($parts[0] === "login" && $method === "POST") {
      $data = json_decode(file_get_contents("php://input"));
      $this->authController->login($data);
      return;
    }

    // CRUD RESTful
    if ($parts[0] === "users") {
      $id = $parts[1] ?? null;  // se tiver ID na rota

      switch ($method) {
        case "POST": // Criar usuário
          $data = json_decode(file_get_contents("php://input"));
          $this->userController->create($data);
          return;

        case "GET":
          if ($id) {
            // GET /users/{id} -> mostra um usuário
            $this->userController->show($id);
          } else {
            // GET /users -> lista usuários
            $this->userController->index();
          }
          return;

        case "PUT":
        case "PATCH":
          if ($id) {
            $data = json_decode(file_get_contents("php://input"));
            $this->userController->update($id, $data);
          } else {
            http_response_code(400);
            echo json_encode(["error" => "ID obrigatório para atualização"]);
          }
          return;

        case "DELETE":
          if ($id) {
            $this->userController->delete($id);
          } else {
            http_response_code(400);
            echo json_encode(["error" => "ID obrigatório para exclusão"]);
          }
          return;

        default:
          http_response_code(405);
          echo json_encode(["error" => "Método não permitido"]);
          return;
      }
    }
  }
}