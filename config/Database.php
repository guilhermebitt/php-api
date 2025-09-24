<?php
// Classe que cria uma conexão com o banco de dados
class Database
{
  // Atributos privados da classe
  private $host = 'localhost';
  private $database = 'crud_api';
  private $user = 'guilherme';
  private $pass = 'B-Ljh@P._MWA4dHR';

  // Função que realiza a conexão
  public function connect() {
    try {
      // Tenta retornar o objeto da conexão
      return new PDO(
        "mysql:host=$this->host;dbname=$this->database",
        $this->user,
        $this->pass,
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
