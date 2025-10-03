<?php
// Classe que cria uma conexão com o banco de dados
class Database
{
  // Atributos privados da classe
  private $host;
  private $database;
  private $user;
  private $password;

  // Método construtor que adquire as credenciais do banco de dados
  public function __construct()
  {
    $config = require __DIR__ . '/../../config/config.php'; // carrega as configs
    $this->host     = $config['host'];
    $this->database = $config['database'];
    $this->user     = $config['user'];
    $this->password = $config['password'];
  }

  // Função que realiza a conexão
  public function connect()
  {
    try {
      // Tenta retornar o objeto da conexão
      return new PDO(
        "mysql:host=$this->host;dbname=$this->database",
        $this->user,
        $this->password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
      );
    } catch (PDOException $e) {
      // Retorna a resposta em caso de erro
      http_response_code(500);
      echo json_encode(["error" => "Erro na conexão: " . $e->getMessage()]);
      exit;
    }
  }
}
