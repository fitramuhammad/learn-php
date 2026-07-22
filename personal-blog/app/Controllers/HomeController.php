<?php

namespace App\Controllers;

use App\Models\ArticleRepository;

class HomeController
{
  public function index()
  {
    return view("Home", ArticleRepository::getArticles());
  }
}
