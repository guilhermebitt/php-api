<?php
require_once __DIR__ . "/../../config/Database.php";

// Classe que controlará o banco de dados via Model
class UserModel
{
  // Conexão com o banco de dados (atributo privado)
  private $db;

  // Método construtor (cria a conexão com o bd)
  public function __construct()
  {
    $this->db = (new Database)->connect();
  }

  // GET * Users
  public function getAllUsers()
  {
    try {
      // Consulta SQL para adquirir todos os usuários
      $sql = "SELECT id, username, email, phone FROM users";

      // Preparando a consulta
      $stmt = $this->db->prepare($sql);

      // Executando a query e a retornando
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
      // EM CASO DE ERRO
      return "Erro: " . $e->getMessage();
    }
  }

  // GET User pelo email
  public function getUserByEmail($email)
  {
    try {
      // Consulta SQL para verificar se o usuário já existe na tabela
      $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return "Erro: " . $e->getMessage();
    }
  }

  // GET User pelo id
  public function getUserById($id)
  {
    try {
      // Consulta SQL para verificar se o usuário já existe na tabela
      $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_STR);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return "Erro: " . $e->getMessage();
    }
  }

  // CREATE
  public function createUser($username, $email, $pass, $phone = null)
  {
    try {
      // Verifica se já existe um usuário
      if ($this->getUserByEmail($email)) {
        return "Usuário já existente";
      }

      // Prepara a query SQL para criar um novo usuário
      $sql = "INSERT INTO users (username, email, pass_hash, phone) VALUES (:username, :email, :pass_hash, :phone)";
      $stmt = $this->db->prepare($sql);
      $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

      // Configura os parâmetros
      $stmt->bindParam(":username", $username, PDO::PARAM_STR);
      $stmt->bindParam(":email", $email, PDO::PARAM_STR);
      $stmt->bindParam(":pass_hash", $hashedPass, PDO::PARAM_STR);
      $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);

      // Executa a query e retorna uma mensagem
      return $stmt->execute() ? "Cadastro criado com sucesso!" : "Erro ao cadastrar usuário.";
    } catch (PDOException $e) {
      // Retorna uma mensagem de erro em caso de algo ocorrer errado
      return "Erro: " . $e->getMessage();
    }
  }

  // UPDATE
  public function updateUser($id, $data)
  {
    // Campos e parâmetros da consulta
    $fields = [];
    $params = [':id' => $id];

    // Atualizar o nome de usuário
    if (isset($data->username)) {
      $fields[] = "username = :newUsername";
      $params[':newUsername'] = $data->username;
    }
    // Atualizar o email
    if (isset($data->email)) {
      $fields[] = "email = :newEmail";
      $params[':newEmail'] = $data->email;
    }
    // Atualizar a senha
    if (isset($data->pass)) {
      $fields[] = "pass_hash = :newPassHash";
      $params[':newPassHash'] = password_hash($data->pass, PASSWORD_DEFAULT);
    }
    // Atualizar o telefone
    if (isset($data->phone)) {
      $fields[] = "phone = :newPhone";
      $params[':newPhone'] = $data->phone;
    }
    // Caso não tenha nenhum campo
    if (empty($fields)) {
      return "Nada para atualizar.";
    }

    // Query para atualizar o usuário
    $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = :id";
    $stmt = $this->db->prepare($sql);

    // Retorna a consulta executada
    return $stmt->execute($params) ? "Cadastro atualizado com sucesso!" : "Erro ao atualizar.";
  }

  // DELETE
  public function deleteUser($id)
  {
    // Consulta para deletar o usuário por ID
    $sql = "DELETE FROM users WHERE id=:id";

    // Prepara a consulta
    $stmt = $this->db->prepare($sql);

    // Configura os parâmetros
    $stmt->bindParam("id", $id, PDO::PARAM_INT);

    // Executa e retorna a consulta
    return $stmt->execute() ? "Usuário excluído com sucesso!" : "Erro ao excluir.";
  }
}
