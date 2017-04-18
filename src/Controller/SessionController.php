<?php
namespace Todo\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\Api\ControllerProviderInterface;

class SessionController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/login/', [$this, 'loginPage'])->bind('login_page');
        $controllers->post('/login/', [$this, 'login'])->bind('login');
        $controllers->post('/logout/', [$this, 'logout'])->bind('logout');
        return $controllers;
    }

    public function loginPage(Application $app)
    {
        if ($this->getCurrentUser($app)) {
            $app['session']->getFlashBag()->add('message', 'already logged in');
            return $app->redirect($app['url_generator']->generate('home'));
        }
        return $app['twig']->render('login.twig');
    }

    public function login(Application $app, Request $request)
    {
        $user = $app['orm.em']->getRepository('Todo\User')->findOneBy([
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
    }

    public function logout(Application $app)
    {
        if (!$this->getCurrentUser($app)) {
            $app['session']->getFlashBag()->add('message', 'not logged in');
            return $app->redirect($app['url_generator']->generate('login_page'));
        }
        $app['session']->remove('user');
        $app['session']->getFlashBag()->add('message', 'successfully logged out');
        return $app->redirect($app['url_generator']->generate('login_page'));
    }

    public static function getCurrentUser(Application $app)
    {
        $session_user = $app['session']->get('user');
        if ($session_user and $session_user['id']) {
            $user = $app['orm.em']->find('Todo\User', $session_user['id']);
            return $user;
        }
        return null;
    }
}
