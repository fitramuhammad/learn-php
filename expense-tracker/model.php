<?php

namespace Model\Expense;

class Argument
{
  public string $argument;
  public string $value;

  public function __construct(string $argument, string $value)
  {
    $this->argument = $argument;
    $this->value = $value;
  }
}
