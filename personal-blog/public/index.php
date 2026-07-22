<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Controllers\AdminController;
use App\Controllers\BlogController;
use App\Controllers\HomeController;
use App\Core\Router;

Router::get("/", [HomeController::class, "index"]);
Router::get("/blog/{id}", [BlogController::class, "detail"]);
Router::get("/admin", [AdminController::class, "login"]);
Router::get("/new", [AdminController::class, "new"]);
Router::post("/new", [BlogController::class, "add"]);
Router::get("/edit/{id}", [BlogController::class, "edit"]);
Router::post("/edit/{id}", [BlogController::class, "edit"]);
Router::post("/delete/{id}", [BlogController::class, "delete"]);

Router::run();
