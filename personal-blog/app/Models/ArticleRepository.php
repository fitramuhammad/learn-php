<?php

namespace App\Models;

use App\Models\Article;
use DateTime;

class ArticleRepository
{
  private static string $filename = __DIR__ . "/../../articles.json";
  private static int $nextId = 0;

  public static function addArticle(string $title, string $content, string $date)
  {
    $articles = self::getArticles();
    $lastArticle = end($articles);
    self::$nextId = $lastArticle ? $lastArticle->id + 1 : 1;
    $date = new DateTime($date)->format("F d, Y");
    $id = self::$nextId;
    $articles[] = new Article($id, $title, $date, $content);

    $data = array_map(fn($item) => [
      "id" => $item->id,
      "title" => $item->title,
      "date" => $item->date,
      "content" => $item->content
    ], $articles);

    self::$nextId++;
    file_put_contents(self::$filename, json_encode($data, JSON_PRETTY_PRINT));
  }

  public static function getArticles(): array
  {
    if ($articles = file_get_contents(self::$filename)) {
      $json = json_decode($articles);
      return array_map(fn($item) => new Article($item->id, $item->title, $item->date, $item->content), $json);
    }

    return [];
  }

  public static function edit(string $id, string $title, string $date, string $content): void
  {
    $articles = self::getArticles();
    foreach ($articles as $article) {
      if ($article->id == $id) {
        $article->edit($title, $date, $content);
        break;
      }
    }

    file_put_contents(self::$filename, json_encode($articles));
  }

  public static function delete(string $id)
  {
    $articles = array_values(array_filter(self::getArticles(), fn($article) => $article->id != $id));

    file_put_contents(self::$filename, json_encode($articles));
  }
}
