<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/helpers.php';

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Request;

$app = new Application();
$app['debug'] = true;
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
				'namespace' => 'User',
				'path' => __DIR__,
			],
			[
				'type' => 'annotation',
				'namespace' => 'Task',
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
$app['twig']->addFunction(new Twig_Function('current_user', 'current_user'));

$app->get('/', function() use ($app) {
	return $app['twig']->render('home.twig');
})->bind('home');

$app->get('/users/', function () use ($app) {
	$users = $app['orm.em']->getRepository('User')->findAll();
	return $app['twig']->render('users.twig', ['users' => $users]);
})->bind('users');

$app->post('/users/', function (Request $request) use ($app) {
	$user = new User();
	$user->name = $request->get('name');
	$user->username = $request->get('username');
	$user->email = $request->get('email');
	$user->password = password_hash($request->get('password'), PASSWORD_DEFAULT);
	$app['orm.em']->persist($user);
	try {
		$app['orm.em']->flush();
	} catch (NotNullConstraintViolationException $e) {
		$app['session']->getFlashBag()->add('message', 'please fill in all fields');
		return $app->redirect($app['url_generator']->generate('login_page'));
	} catch (UniqueConstraintViolationException $e) {
		$app['session']->getFlashBag()->add('message', 'choose another username or email');
		return $app->redirect($app['url_generator']->generate('login_page'));
	}
	$app['session']->set('user', ['id' => $user->id]);
	return $app->redirect($app['url_generator']->generate('users'));
})->bind('add_user');

$app->get('/login/', function () use ($app) {
	if (current_user($app)) {
		$app['session']->getFlashBag()->add('message', 'already logged in');
		return $app->redirect($app['url_generator']->generate('home'));
	}
	return $app['twig']->render('login.twig');
})->bind('login_page');

$app->post('/login/', function (Request $request) use ($app) {
	$user = $app['orm.em']->getRepository('User')->findOneBy([
		'username' => $request->get('username')
	]);
	if (!$user) {
		$app['session']->getFlashBag()->add('message', 'no such user');
		return $app->redirect($app['url_generator']->generate('login_page'));
	}
	if (!password_verify($request->get('password'), $user->password)) {
		$app['session']->getFlashBag()->add('message', 'wrong password');
		return $app->redirect($app['url_generator']->generate('login_page'));
	}
	$app['session']->getFlashBag()->add('message', 'login success');
	$app['session']->set('user', ['id' => $user->id]);
	return $app->redirect($app['url_generator']->generate('home'));
})->bind('login');

$app->post('/logout/', function() use ($app) {
	if (!current_user($app)) {
		$app['session']->getFlashBag()->add('message', 'not logged in');
		return $app->redirect($app['url_generator']->generate('login_page'));
	}
	$app['session']->remove('user');
	$app['session']->getFlashBag()->add('message', 'successfully logged out');
	return $app->redirect($app['url_generator']->generate('login_page'));
})->bind('logout');

return $app;
