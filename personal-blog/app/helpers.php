<?php

if (!function_exists("view")) {
  function view(string $viewPath, array $data = []): string
  {
    $file = __DIR__ . "/Views/$viewPath/index.php";

    if (!file_exists($file)) {
      throw new Exception("Error: View '{$viewPath}' not found in {$file}");
    }

    extract($data);

    ob_start();

    require $file;

    $htmlContent = ob_get_clean();

    return $htmlContent;
  }
}
