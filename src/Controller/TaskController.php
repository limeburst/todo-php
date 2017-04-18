<?php
namespace Todo\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\Api\ControllerProviderInterface;

use Todo\Task;

class TaskController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->post('/', [$this, 'addTask'])->bind('add_task');
        $controllers->post('/done/', [$this, 'finishTask'])->bind('finish_task');
        $controllers->post('/doing/', [$this, 'unfinishTask'])->bind('unfinish_task');
        return $controllers;
    }

    public function addTask(Application $app, Request $request)
    {
        $user = SessionController::getCurrentUser($app);
        if (!$user) {
            $app['session']->getFlashBag()->add('message', 'not logged in');
            return $app->redirect($app['url_generator']->generate('login_page'));
        }
        $task = new Task();
        $task->done = false;
        $task->name = $request->get('name');
        $task->owner = $user;
        $app['orm.em']->persist($task);
        $app['orm.em']->flush();
        $app['session']->getFlashBag()->add('message', 'task added');
        return $app->redirect($app['url_generator']->generate('home'));
    }

    public function finishTask(Application $app, Request $request)
    {
        $user = SessionController::getCurrentUser($app);
        if (!$user) {
            $app['session']->getFlashBag()->add('message', 'not logged in');
            return $app->redirect($app['url_generator']->generate('login_page'));
        }
        $task = $app['orm.em']->find('Todo\Task', $request->get('id'));
        if ($task->owner !== $user) {
            $app['session']->getFlashBag()->add('message', 'you are not the task owner');
            return $app->redirect($app['url_generator']->generate('home'));
        }
        $task->done = true;
        $app['orm.em']->persist($task);
        $app['orm.em']->flush();
        $app['session']->getFlashBag()->add('message', 'task done!');
        return $app->redirect($request->headers->get('referer'));
    }

    public function unfinishTask(Application $app, Request $request)
    {
        $user = SessionController::getCurrentUser($app);
        if (!$user) {
            $app['session']->getFlashBag()->add('message', 'not logged in');
            return $app->redirect($app['url_generator']->generate('login_page'));
        }
        $task = $app['orm.em']->find('Todo\Task', $request->get('id'));
        if ($task->owner !== $user) {
            $app['session']->getFlashBag()->add('message', 'you are not the task owner');
            return $app->redirect($app['url_generator']->generate('home'));
        }
        $task->done = false;
        $app['orm.em']->persist($task);
        $app['orm.em']->flush();
        $app['session']->getFlashBag()->add('message', 'task undone');
        return $app->redirect($request->headers->get('referer'));
    }
}
