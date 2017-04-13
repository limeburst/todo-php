<?php
use Silex\Application;

function current_user(Application $app)
{
	$session_user = $app['session']->get('user');
	if ($session_user and $session_user['id']) {
		$user = $app['orm.em']->find('Todo\User', $session_user['id']);
		return $user;
	}
	return null;
}
