<?php
require_once __DIR__.'/../vendor/autoload.php';

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;

use Todo\HomeControllerProvider;
use Todo\SessionControllerProvider;
use Todo\TaskControllerProvider;
use Todo\UserControllerProvider;

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
				'namespace' => 'Todo\User',
				'path' => __DIR__,
			],
			[
				'type' => 'annotation',
				'namespace' => 'Todo\Task',
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
    new Twig_Function('current_user', 'Todo\SessionControllerProvider::getCurrentUser')
);

$app->mount('/', new HomeControllerProvider());
$app->mount('/', new SessionControllerProvider());
$app->mount('/tasks/', new TaskControllerProvider());
$app->mount('/users/', new UserControllerProvider());

return $app;
