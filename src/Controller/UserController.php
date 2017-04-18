<?php
namespace Todo\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

use Todo\Entity\UserEntity;

class UserController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', [$this, 'users'])->bind('users');
        $controllers->post('/', [$this, 'addUser'])->bind('add_user');
        $controllers->get('/{username}/', [$this, 'profile'])->bind('user');
        $controllers->get('/{username}/tasks', [$this, 'tasks'])->bind('user_tasks');
        return $controllers;
    }

    public function users(Application $app)
    {
        $users = $app['orm.em']->getRepository('Todo\Entity\UserEntity')->findAll();
        return $app['twig']->render('users.twig', ['users' => $users]);
    }

    public function addUser(Application $app, Request $request)
    {
        $user = new UserEntity();
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
        $user = $app['orm.em']->getRepository('Todo\Entity\UserEntity')->findOneBy(['username' => $username]);
        if (!$user) {
            $app->abort(404, 'user does not exist.');
        }
        return $app['twig']->render('user.twig', ['user' => $user]);
    }

    public function tasks(Application $app, $username)
    {
        $user = $app['orm.em']->getRepository('Todo\Entity\UserEntity')->findOneBy(['username' => $username]);
        $tasks = array_map(function ($task) use ($user) {
            return [
                'id' => $task->id,
                'name' => $task->name,
                'done' => $task->done,
                'owner' => $task->owner->username,
            ];
        }, $user->tasks->getValues());
        return $app->json(array_reverse($tasks));
    }
}
