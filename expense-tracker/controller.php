<?php

namespace Controller;

require_once "model.php";

use DateTime;
use Model\Expense\Argument;

class ExpenseController
{
  public static array $expenses = [];
  private static $filename = "expense.json";

  private static function read()
  {
    $content = file_get_contents(self::$filename);
    self::$expenses = $content ? json_decode($content) : [];
  }

  private static function write()
  {
    file_put_contents(self::$filename, json_encode(self::$expenses));
  }

  public static function list()
  {
    self::read();

    if (empty(self::$expenses)) {
      echo "Expenses is empty" . PHP_EOL;
      exit(1);
    }

    echo "ID\tDate\t\tDescription\tAmount" . PHP_EOL;
    foreach (self::$expenses as $expense) {
      echo "$expense->id\t$expense->date\t $expense->description\t\t $$expense->amount" . PHP_EOL;
    }
  }

  public static function add_expense(array $expense): void
  {
    # add expense to file expense.json
    if (count($expense) != 2 || ($expense[0]->argument != "description" && $expense[1]->description != "amount")) {
      echo "USAGE: expense-tracker add --description <DESCRIPTION> --amount <AMOUNT>" . PHP_EOL;
      exit(1);
    }

    self::read();
    $id = count(self::$expenses) + 1;

    self::$expenses[] = [
      "id" => $id,
      "date" => date("Y-m-d"),
      "description" => $expense[0]->value,
      "amount" => $expense[1]->value
    ];

    self::write();
    echo "Expense added successfully (ID: $id)" . PHP_EOL;
  }

  public static function delete(array $args)
  {
    # delete expense from file expense.json
    if (count($args) != 1 || $args[0]->argument != "id") {
      echo "USAGE: expense-tracker delete --id <ID>" . PHP_EOL;
      exit(1);
    }

    self::read();

    $id = $args[0]->value;

    foreach (self::$expenses as $expense) {
      if ($expense->id == $id) {
        self::$expenses = array_filter(self::$expenses, fn($value) => $value->id != $id);
        self::write();
        echo "Expense deleted successfully" . PHP_EOL;
        return;
      }
    }

    echo "Id doesn't exist" . PHP_EOL;
  }

  public static function summary(array $args)
  {
    self::read();

    if (!count(self::$expenses)) {
      echo "expenses is empty" . PHP_EOL;
      exit(1);
    }

    if (count($args) && $args[0]->argument == "month") {
      $filtered_expenses = array_filter(self::$expenses, fn($value) => new DateTime($value->date)->format("n") == $args[0]->value);
      $summary = array_reduce($filtered_expenses, fn($carry, $item) => $carry + $item->amount, 0);
      $dateObj = DateTime::createFromFormat("!m", $args[0]->value)->format("F");

      echo "Total expenses for $dateObj: $$summary" . PHP_EOL;
    } else {
      $summary = array_reduce(self::$expenses, fn($carry, $item) => $carry + $item->amount, 0);
      echo "Total expenses: $$summary" . PHP_EOL;
    }
  }
}

class ArgumentController
{
  private static array $arguments = [];

  public static function parse(array $arg)
  {
    for ($i = 2; $i < count($arg); $i++) {
      if (str_starts_with($arg[$i], "--")) {
        $key = str_replace("--", "", $arg[$i]);
        self::$arguments[] = new Argument($key, $arg[$i + 1]);
        $i++;
      }
    }
    return self::$arguments;
  }

  public static function help()
  {
    echo "\x1b[33mUsage: \x1b[0mexpense-tracker [COMMAND] [OPTIONS]" . PHP_EOL;
    echo <<<OPTIONS
  \n\x1b[33mOptions: \x1b[0m
    --description       Add description
    --amount            Add amount
    --id                Id for deletion
    --month             Month for summary
  OPTIONS . PHP_EOL;
    echo <<<COMMAND
  \n\x1b[33mCommands: \x1b[0m
    add       Add expense with description and amount
    delete    Delete an expense
    list      List all expenses
    summary   Summary expenses
  COMMAND . PHP_EOL;
  }
}
