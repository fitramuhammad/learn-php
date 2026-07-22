<?php

namespace App\Core;

use ReflectionMethod;

class Router
{
  private static array $routes;

  public static function get(string $path, callable | array $callback): void
  {
    self::$routes["GET"][$path] = $callback;
  }

  public static function post(string $path, callable | array $callback)
  {
    self::$routes['POST'][$path] = $callback;
  }

  public static function run()
  {
    $method = $_SERVER["REQUEST_METHOD"];
    $path = isset($_SERVER["PATH_INFO"]) ? $_SERVER["PATH_INFO"] : "/";

    if ($callback = self::$routes[$method][$path]) {
      return self::execute($callback);
    }

    foreach (self::$routes[$method] as $routePath => $c) {
      $pattern = "#^" . preg_replace(['/\{id\}/', '/\{[a-zA-Z0-9_]+\}/'], ['([0-9]+)', '([^/]+)'], $routePath) . "$#";

      if (preg_match($pattern, $path, $matches)) {
        array_shift($matches);

        return self::execute($c, $matches);
      }
    }

    http_response_code(404);
    echo "Page not found";
  }

  private static function execute(array $callback, array $params = [])
  {
    if (is_callable($callback) && !is_array($callback)) {
      return call_user_func_array($callback, $params);
    }

    if (is_array($callback)) {
      $controllerName = $callback[0];
      $methodName = $callback[1];

      $reflectionMethod = new ReflectionMethod($controllerName, $methodName);

      if ($reflectionMethod->isStatic()) {
        $response = call_user_func_array([$controllerName, $methodName], $params);
      } else {
        $controllerInstance = new $controllerName();
        $response = call_user_func_array([$controllerInstance, $methodName], $params);
      }

      if (is_string($response)) {
        echo $response;
      }
      return;
    }
  }
}
