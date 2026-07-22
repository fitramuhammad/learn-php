<?php

namespace App\Core;

use CurlHandle;

class Http
{
  private CurlHandle $ch;

  public function __construct()
  {
    $this->ch = curl_init();
  }

  public function request(string $method, string $url)
  {
    $ch = $this->ch;

    curl_reset($ch);

    curl_setopt_array($ch, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => strtoupper($method),
      CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
    ]);

    $response = curl_exec($ch);

    if ($error = curl_error($ch)) {
      return json_encode([
        "error" => [
          "message" => $error
        ]
      ]);
    } else {
      return $response;
    }
  }
}
