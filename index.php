<?php
ob_start();
date_default_timezone_set('America/Bahia');
require __DIR__ . "/vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use CoffeeCode\Router\Router;
use Source\Core\Session;

$session = new Session();
$route = new Router(url(), ":");
$route->namespace("Source\App");

/**
 * WEB ROUTES
 */
$route->group(null);
$route->get("/", "Web:home");

//auth
$route->group(null);
$route->get("/", "Web:login");
$route->get("/entrar", "Web:login");
$route->post("/entrar", "Web:login");
$route->get("/cadastrar", "Web:register");
$route->post("/cadastrar", "Web:register");
$route->get("/recuperar", "Web:forget");
$route->post("/recuperar", "Web:forget");
$route->get("/recuperar/{code}", "Web:reset");
$route->post("/recuperar/resetar", "Web:reset");

/**
 * APP
 */
$route->group("/app");
$route->get("/", "App:home");
$route->get("/aberto", "App:income");
$route->get("/aberto/{status}/{category}/{date}", "App:income");
$route->get("/atividades", "App:expense");
$route->get("/atividades/{status}/{category}/{date}", "App:expense");
$route->get("/chamado/{invoice_id}", "App:invoice");

$route->get("/perfil", "App:profile");
$route->get("/sair", "App:logout");

$route->post("/launch", "App:launch");
$route->post("/invoice/{invoice}", "App:invoice");
$route->post("/remove/{invoice}", "App:remove");
$route->post("/onpaid", "App:onpaid");
$route->post("/filter", "App:filter");
$route->post("/profile", "App:profile");




/**
 * ERROR ROUTES
 */
$route->group("/ops");
$route->get("/{errcode}", "Web:error");

/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();
