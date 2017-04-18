<?php
require_once __DIR__.'/../vendor/autoload.php';

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
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
$app->register(new DoctrineOrmServiceProvider(), [
	'orm.em.options' => [
		'mappings' => [
			[
				'type' => 'annotation',
				'namespace' => 'Todo\Entity\UserEntity',
				'path' => __DIR__,
			],
			[
				'type' => 'annotation',
				'namespace' => 'Todo\Entity\TaskEntity',
				'path' => __DIR__,
			],
		],
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
