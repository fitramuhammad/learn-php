<?php

namespace App\Controllers;

use App\Models\ArticleRepository;

class BlogController
{
  public function detail(string $id)
  {
    $data = array_values(array_filter(ArticleRepository::getArticles(), fn($blog) => $blog->id == $id));
    return view("Blog", $data);
  }

  public function add()
  {
    $title = $_POST["title"];
    $date = $_POST["date"];
    $content = $_POST["content"];
    ArticleRepository::addArticle($title, $content, $date);
    header("Location: /admin");
    exit;
  }

  public function edit(string $id)
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $title = $_POST["title"];
      $date = $_POST["date"];
      $content = $_POST["content"];

      ArticleRepository::edit($id, $title, $date, $content);

      header("Location: /admin");
      exit;
    }

    $data = array_values(array_filter(ArticleRepository::getArticles(), fn($blog) => $blog->id == $id));
    return view("Edit", $data);
  }

  public function delete(string $id)
  {
    ArticleRepository::delete($id);

    header("Location: /admin");
    exit;
  }
}
