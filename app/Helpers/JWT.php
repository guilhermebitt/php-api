<?php
/*
  O JWT (Jason W... sla oq), é responsável por
  gerar e validar tokens.
*/

class JWT
{
  // Definição de variáveis privadas
  private static $secret;

  // Método construtor da classe
  public function __construct()
  {
    $config = require __DIR__ . '/../../config/config.php'; // carrega as configs
    $this->secret = $config['secret'];
  }

  // Codificação Base64 URL Safe
  private static function base64UrlEncode($data)
  {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
  }

  // Decodificação Base64 URL Safe
  private static function base64UrlDecode($data)
  {
    return base64_decode(strtr($data, '-_', '+/'));
  }

  // Gerar token
  public static function generate($payload, $exp = 3600)
  {
    $header = json_encode(["alg" => "HS256", "typ" => "JWT"]);

    // adiciona expiração
    $payload['exp'] = time() + $exp;

    $base64Header = self::base64UrlEncode($header);
    $base64Payload = self::base64UrlEncode(json_encode($payload));

    $signature = hash_hmac('sha256', "$base64Header.$base64Payload", self::$secret, true);
    $base64Signature = self::base64UrlEncode($signature);

    return "$base64Header.$base64Payload.$base64Signature";
  }
}
