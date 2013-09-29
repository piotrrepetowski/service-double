<?php

require_once __DIR__ . '/../vendor/autoload.php';

$request = new \Zend\Http\PhpEnvironment\Request();

$loader = new \ServiceDouble\Request\Loader();
$facade = new \ServiceDouble\Facade();

foreach ($loader->get(__DIR__ . '/../config/config.ini', $request) as $handler)
{
    $facade->registerHandler($handler);
}

$facade->handle($request);

