<?php
namespace Todo;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class UserControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', [$this, 'users'])->bind('users');
        $controllers->post('/', [$this, 'addUser'])->bind('add_user');
        $controllers->get('/{username}/', [$this, 'profile'])->bind('user');
        return $controllers;
    }

    public function users(Application $app)
    {
        $users = $app['orm.em']->getRepository('Todo\User')->findAll();
        return $app['twig']->render('users.twig', ['users' => $users]);
    }

    public function addUser(Application $app, Request $request)
    {
        $user = new User();
        $user->name = $request->get('name');
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->password = password_hash($request->get('password'), PASSWORD_DEFAULT);
        if (!$user->name || !$user->username || !$user->email || !$user->password) {
            $app['session']->getFlashBag()->add('message', 'please fill in all fields');
            return $app->redirect($app['url_generator']->generate('login_page'));
        }
        $app['orm.em']->persist($user);
        try {
            $app['orm.em']->flush();
        } catch (UniqueConstraintViolationException $e) {
            $app['session']->getFlashBag()->add('message', 'choose another username or email');
            return $app->redirect($app['url_generator']->generate('login_page'));
        }
        $app['session']->set('user', ['id' => $user->id]);
        return $app->redirect($app['url_generator']->generate('users'));
    }

    public function profile(Application $app, $username)
    {
        $user = $app['orm.em']->getRepository('Todo\User')->findOneBy(['username' => $username]);
        if (!$user) {
            $app->abort(404, 'user does not exist.');
        }
        return $app['twig']->render('user.twig', ['user' => $user]);
    }
}