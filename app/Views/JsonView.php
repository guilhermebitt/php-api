<?php
// Classe de View. Retorna um json como "view" para o front-end
class JsonView
{
  /**
   * Converte um array ou objeto em JSON e envia para o front-end.
   *
   * @param mixed $data Dados a serem convertidos em JSON (array ou objeto).
   * @param int $statusCode Código HTTP da resposta (padrão: 200).
   * @return void Não retorna valor; envia a resposta HTTP diretamente.
   */
  public static function render($data, $statusCode = 200)
  {
    header("Content-Type: application/json; charset=UTF-8");
    http_response_code($statusCode);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }
}
