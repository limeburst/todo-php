<?php
declare(strict_types=1);

namespace Todo\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\Api\ControllerProviderInterface;

use Todo\EntityManagerProvider;
use Todo\Entity\TaskEntity;
use Todo\Repository\TaskRepository;

class TaskController implements ControllerProviderInterface
{
    /**
     * @param Application $app
     * @return mixed
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->post('/', [$this, 'addTask'])->bind('add_task');
        $controllers->post('/done/', [$this, 'finishTask'])->bind('finish_task');
        $controllers->post('/doing/', [$this, 'unfinishTask'])->bind('unfinish_task');
        return $controllers;
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addTask(Application $app, Request $request)
    {
        $user = SessionController::getCurrentUser($app);
        if (!$user) {
            $app['session']->getFlashBag()->add('message', 'not logged in');
            $login_page_url = $app['url_generator']->generate('login_page');
            return $app->redirect($login_page_url);
        }
        $t_name = $request->get('name');
        $task = new TaskEntity($t_name, $user, false);
        $em = EntityManagerProvider::getEntityManager();
        $em->persist($task);
        $em->flush();
        $app['session']->getFlashBag()->add('message', 'task added');
        $home_url = $app['url_generator']->generate('home');
        return $app->redirect($home_url);
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function finishTask(Application $app, Request $request)
    {
        $user = SessionController::getCurrentUser($app);
        if (!$user) {
            $app['session']->getFlashBag()->add('message', 'not logged in');
            $login_page_url = $app['url_generator']->generate('login_page');
            return $app->redirect($login_page_url);
        }
        $t_id = $request->get('id');
        $task = TaskRepository::getRepository()->findOneById($t_id);
        if ($task->owner !== $user) {
            $app['session']->getFlashBag()->add('message', 'you are not the task owner');
            $home_url = $app['url_generator']->generate('home');
            return $app->redirect($home_url);
        }
        $task->is_done = true;
        $em = EntityManagerProvider::getEntityManager();
        $em->persist($task);
        $em->flush();
        $app['session']->getFlashBag()->add('message', 'task done!');
        $referer_url = $request->headers->get('referer');
        return $app->redirect($referer_url);
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unfinishTask(Application $app, Request $request)
    {
        $user = SessionController::getCurrentUser($app);
        if (!$user) {
            $app['session']->getFlashBag()->add('message', 'not logged in');
            $login_page_url = $app['url_generator']->generate('login_page');
            return $app->redirect($login_page_url);
        }
        $t_id = (int) $request->get('id');
        $task = TaskRepository::getRepository()->findOneById($t_id);
        if ($task->owner !== $user) {
            $app['session']->getFlashBag()->add('message', 'you are not the task owner');
            $home_url = $app['url_generator']->generate('home');
            return $app->redirect($home_url);
        }
        $task->is_done = false;
        $em = EntityManagerProvider::getEntityManager();
        $em->persist($task);
        $em->flush();
        $app['session']->getFlashBag()->add('message', 'task undone');
        $referer_url = $request->headers->get('referer');
        return $app->redirect($referer_url);
    }
}
