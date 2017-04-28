<?php
declare(strict_types=1);

namespace Todo\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\Api\ControllerProviderInterface;

use Todo\Domain\User\Model\UserEntity;
use Todo\Domain\User\Repository\UserRepository;

class LoginController implements ControllerProviderInterface
{
    /**
     * @param Application $app
     * @return mixed
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/login/', [$this, 'loginPage'])->bind('login_page');
        $controllers->post('/login/', [$this, 'login'])->bind('login');
        $controllers->post('/logout/', [$this, 'logout'])->bind('logout');
        return $controllers;
    }

    /**
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginPage(Application $app)
    {
        if ($this->getCurrentUser($app)) {
            $app['session']->getFlashBag()->add('message', 'already logged in');
            $home_url = $app['url_generator']->generate('home');
            return $app->redirect($home_url);
        }
        return $app['twig']->render('login.twig');
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function login(Application $app, Request $request)
    {
        $u_username = $request->get('username');
        $user = UserRepository::getRepository()->findOneByUsername($u_username);
        $login_page_url = $app['url_generator']->generate('login_page');
        if (!$user) {
            $app['session']->getFlashBag()->add('message', 'no such user');
            return $app->redirect($login_page_url);
        }
        if (!password_verify($request->get('password'), $user->getPassword())) {
            $app['session']->getFlashBag()->add('message', 'wrong password');
            return $app->redirect($login_page_url);
        }
        $app['session']->getFlashBag()->add('message', 'login success');
        $app['session']->set('user', ['id' => $user->getId()]);
        $home_url = $app['url_generator']->generate('home');
        return $app->redirect($home_url);
    }

    /**
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function logout(Application $app)
    {
        $login_page_url = $app['url_generator']->generate('login_page');
        if (!$this->getCurrentUser($app)) {
            $app['session']->getFlashBag()->add('message', 'not logged in');
            return $app->redirect($login_page_url);
        }
        $app['session']->remove('user');
        $app['session']->getFlashBag()->add('message', 'successfully logged out');
        return $app->redirect($login_page_url);
    }

    /**
     * @param Application $app
     * @return null|UserEntity
     */
    public static function getCurrentUser(Application $app)
    {
        $session_user = $app['session']->get('user');
        if ($session_user and $session_user['id']) {
            $user = UserRepository::getRepository()->findOneById($session_user['id']);
            return $user;
        }
        return null;
    }
}
