<?php
// Application middleware

//configuracion inicializacion de base dato

// e.g: $app->add(new \Slim\Csrf\Guard);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container->get('settings')['db']);

$capsule->setAsGlobal();

$capsule->bootEloquent();



