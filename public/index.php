<?php

require_once __DIR__ . '/../vendor/autoload.php';

$frontHandler = new \ServiceDouble\RequestHandler();

$loader = new \ServiceDouble\Request\Loader();

foreach ($loader->get(__DIR__ . '/../config/config.ini') as $handler)
{
    $frontHandler->registerHandler($handler);
}

$json = file_get_contents('php://input');

$frontHandler->handle($json);
