<?php
declare(strict_types=1);

namespace Todo\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class HomeController implements ControllerProviderInterface
{
    /**
     * @param Application $app
     * @return mixed
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', [$this, 'home'])->bind('home');
        return $controllers;
    }

    /**
     * @param Application $app
     * @return mixed
     */
    public function home(Application $app)
    {
        $user = LoginController::getCurrentUser($app);
        if ($user === null) {
            return $app['twig']->render('home.twig');
        }
        $tasks = $user->getTasks()->getValues();
        if (!empty($tasks)) {
            $doing = array_filter($tasks, function ($task) {
                return !$task->getDone();
            });
        } else {
            $doing = [];
        }
        return $app['twig']->render('home.twig', ['tasks' => $doing]);
    }
}
