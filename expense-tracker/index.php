<?php

require "model.php";
require "controller.php";
require "commands.php";

use Controller\ExpenseController;
use Controller\ArgumentController;
use Enum\Commands;

$command = $argv[1] ?? null;

if (!$command) {
  ArgumentController::help();
  exit(1);
}

$arguments = count($argv) > 1 ? ArgumentController::parse($argv) : [];

switch ($command) {
  case Commands::ADD:
    ExpenseController::add_expense($arguments);
    break;
  case Commands::LIST:
    ExpenseController::list();
    break;
  case Commands::DELETE:
    ExpenseController::delete($arguments);
    break;
  case Commands::SUMMARY:
    ExpenseController::summary($arguments);
    break;
  default:
    ArgumentController::help();
    break;
}
