<?php

require_once __DIR__ . "/vendor/autoload.php";

use App\Core\Http;
use Predis\Client as PredisClient;

$r = new PredisClient();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new Http();

$location = isset($_SERVER["PATH_INFO"]) ? trim($_SERVER["PATH_INFO"], "/") : null;

header('Content-Type: application/json');

$userIp = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (strpos($userIp, ',') !== false) {
  $userIp = explode(',', $userIp)[0];
}
$rateLimitKey = "rate_limit:{$userIp}";
$maxRequests = 10; // max request
$timeWindow = 60; // 60 seconds

$requestsCount = $r->incr($rateLimitKey);
if ($requestsCount === 1) {
  $r->expire($rateLimitKey, $timeWindow);
}

if ($requestsCount > $maxRequests) {
  http_response_code(429);
  echo json_encode([
    "error" => [
      "message" => "Too many requests. Please try again later."
    ]
  ]);
  exit;
}

if ($location) {
  $response = "";
  $sanitizedLocation = urlencode($location);

  if ($res = $r->get($location)) {
    $response = $res;
  } else {
    $response = $client->request("GET", "{$_ENV["BASE_URL"]}{$sanitizedLocation}?key={$_ENV["WEATHER_API_KEY"]}");
    $r->setex($location, 3600, $response);
  }

  if (str_contains($response, "Bad API Request")) {
    http_response_code(400);
    echo json_encode([
      "error" => [
        "message" => ltrim($response, "Bad API Request:")
      ]
    ]);
  } else {
    echo $response;
  }
} else {
  http_response_code(400);
  echo json_encode([
    "error" => [
      "message" => "Missing uri parameter",
    ]
  ]);
}
