<?php

require_once __DIR__ . '/../vendor/autoload.php';

$request = new \Zend\Http\PhpEnvironment\Request();

$loader = new \ServiceDouble\Request\Handler\Loader();
$facade = new \ServiceDouble\Facade();

foreach ($loader->get(__DIR__ . '/../config/config.xml', $request) as $handler)
{
    $facade->registerHandler($handler);
}

try
{
    $facade->handle($request);
}
catch (Exception $e)
{
    echo $e->getMessage();
}

