<?php

declare(strict_types = 1);

use App\Core\App;
use App\Core\Config;
use App\Controllers\HomeController;
use App\Core\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

define('STORAGE_PATH', __DIR__ . '/../storage');
define('VIEW_PATH', __DIR__ . '/../views');

$router = new Router();

$router
    ->get('/', [HomeController::class, 'index'])
    ->get('/transactions', [HomeController::class, 'showTransactions'])
    ->post('/upload', [HomeController::class, 'uploadTransactionsFile']);
    

(new App(
    $router,
    ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
    new Config($_ENV)
))->run();

