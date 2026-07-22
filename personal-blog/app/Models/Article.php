<?php

namespace App\Models;

class Article
{
  public function __construct(public int $id, public string $title, public string $date, public string $content) {}
  public function edit(string $title, string $date, string $content)
  {
    $this->title = $title;
    $this->content = $content;
    $this->date = $date;
  }
}
