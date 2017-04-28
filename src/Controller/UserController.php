<?php
declare(strict_types=1);

namespace Todo\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Todo\Application\User\UserAppService;

class UserController implements ControllerProviderInterface
{
    /**
     * @param Application $app
     * @return mixed
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', [$this, 'users'])->bind('users');
        $controllers->post('/', [$this, 'addUser'])->bind('add_user');
        $controllers->get('/{username}/', [$this, 'profile'])->bind('user');
        $controllers->get('/{username}/tasks', [$this, 'tasks'])->bind('user_tasks');
        return $controllers;
    }

    /**
     * @param Application $app
     * @return mixed
     */
    public function users(Application $app)
    {
        $users = UserAppService::getAllUsers();
        return $app['twig']->render('users.twig', ['users' => $users]);
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addUser(Application $app, Request $request)
    {
        $login_page_url = $app['url_generator']->generate('login_page');

        $u_name = $request->get('name');
        $u_username = $request->get('username');
        $u_email = $request->get('email');
        $u_password = $request->get('password');

        if (!$u_name || !$u_username || !$u_email || !$u_password) {
            $app['session']->getFlashBag()->add('message', 'please fill in all fields');
            return $app->redirect($login_page_url);
        }

        try {
            $user = UserAppService::saveUser($u_name, $u_username, $u_email, $u_password);
        } catch (UniqueConstraintViolationException $e) {
            $app['session']->getFlashBag()->add('message', 'choose another username or email');
            return $app->redirect($login_page_url);
        }

        $app['session']->set('user', ['id' => $user->getId()]);
        
        $users_url = $app['url_generator']->generate('users');
        return $app->redirect($users_url);
    }

    /**
     * @param Application $app
     * @param string $username
     * @return mixed
     */
    public function profile(Application $app, string $username)
    {
        $user = UserAppService::getUserByUsername($username);
        if (!$user) {
            $app->abort(Response::HTTP_NOT_FOUND, 'user does not exist.');
        }
        return $app['twig']->render('user.twig', ['user' => $user]);
    }

    /**
     * @param Application $app
     * @param string $username
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tasks(Application $app, string $username)
    {
        $user = UserAppService::getUserByUsername($username);
        $tasks = array_map(function ($task) use ($user) {
            return [
                'id' => $task->id,
                'name' => $task->name,
                'done' => $task->is_done,
                'owner' => $task->owner->username,
            ];
        }, $user->getTasks()->getValues());
        return $app->json(array_reverse($tasks));
    }
}
