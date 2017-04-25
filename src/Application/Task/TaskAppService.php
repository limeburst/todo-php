<?php
declare(strict_types=1);

namespace Todo\Application\Task;

use Todo\EntityManagerProvider;
use Todo\Domain\Task\Model\TaskEntity;
use Todo\Domain\Task\Repository\TaskRepository;
use Todo\Domain\User\Repository\UserRepository;

class TaskAppService
{
    /**
     * @param string $t_name
     * @param int $u_id
     * @param bool $t_done
     */
    public static function saveTask(string $t_name, int $u_id, bool $t_done)
    {

        $em = EntityManagerProvider::getEntityManager();
        $user = UserRepository::getRepository()->findOneById($u_id);
        $task = new TaskEntity($t_name, $user, $t_done);
        $em->persist($task);
        $em->flush();
    }

    /**
     * @param int $t_id
     * @param int $u_id
     * @throws \Exception
     */
    public static function markTaskAsDone(int $t_id, int $u_id)
    {
        $task = TaskRepository::getRepository()->findOneById($t_id);
        if ($task->owner->id !== $u_id) {
            throw new \Exception('you are not the task owner');
        }
        $task->is_done = true;
        $em = EntityManagerProvider::getEntityManager();
        $em->persist($task);
        $em->flush();
    }

    /**
     * @param int $t_id
     * @param int $u_id
     * @throws \Exception
     */
    public static function markTaskAsDoing(int $t_id, int $u_id)
    {
        $task = TaskRepository::getRepository()->findOneById($t_id);
        if ($task->owner->id !== $u_id) {
            throw new \Exception('you are not the task owner');
        }
        $task->is_done = false;
        $em = EntityManagerProvider::getEntityManager();
        $em->persist($task);
        $em->flush();
    }
}
