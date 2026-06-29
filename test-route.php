<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "Route logout: " . ($app['router']->getRoutes()->getByName('logout') ? 'FOUND' : 'NOT FOUND') . "<br>";
var_dump($app['router']->getRoutes()->getByName('logout'));
