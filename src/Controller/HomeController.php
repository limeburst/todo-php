<?php
namespace Todo\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class HomeController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', [$this, 'home'])->bind('home');
        return $controllers;
    }

    public function home(Application $app)
    {
        $user = SessionController::getCurrentUser($app);
        if (!$user) {
            return $app['twig']->render('home.twig');
        }
        $tasks = $user->tasks->getValues();
        if ($tasks) {
            $doing = array_filter($tasks, function ($task) {
                return !$task->done;
            });
        } else {
            $doing = [];
        }
        return $app['twig']->render('home.twig', ['tasks' => $doing]);
    }
}
