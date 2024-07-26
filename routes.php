<?php

declare(strict_types=1);

$task   = new Task();
$router = new Router();

if ($router->isApiCall()) {
    require 'routes/api.php';
    return;
}

if ($router->isTelegramUpdate()) {
    require 'routes/telegram.php';
    return;
}

require 'routes/web.php';