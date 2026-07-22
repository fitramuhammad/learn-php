<?php

namespace App\Controllers;

use App\Models\ArticleRepository;

class AdminController
{
  public function login()
  {
    $user = $_SERVER["PHP_AUTH_USER"];
    $pass = $_SERVER["PHP_AUTH_PW"];

    $validated = ($user == "admin") && ($pass == "admin");

    if (!$validated) {
      header('WWW-Authenticate: Basic realm="Admin Dashboard"');
      header('HTTP/1.1 401 Unauthorized');
      echo '<a href="/">Home</a>';
      exit;
    }

    return view("Admin", ArticleRepository::getArticles());
  }

  public function new()
  {
    return view("New");
  }
}
