<?php
declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;

use Todo\Controller\HomeController;
use Todo\Controller\SessionController;
use Todo\Controller\TaskController;
use Todo\Controller\UserController;

$app = new Application();
$app['debug'] = true;
$app->register(new ServiceControllerServiceProvider());
$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_mysql',
        'url' => 'mysql://todo:password@localhost/todo',
    ],
]);
$app->register(new RoutingServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new TwigServiceProvider(), [
    'twig.path' => __DIR__.'/templates',
    'twig.options' => ['debug' => true],
]);
$app['twig']->addFunction(
    new Twig_Function('current_user', 'Todo\Controller\SessionController::getCurrentUser')
);

$app->mount('/', new HomeController());
$app->mount('/', new SessionController());
$app->mount('/tasks/', new TaskController());
$app->mount('/users/', new UserController());

return $app;
