<?php
declare(strict_types=1);

namespace Todo\Repository;

use Doctrine\ORM\EntityRepository;
use Silex\Application;

class TaskRepository extends EntityRepository
{
    /**
     * @param Application $app
     * @return TaskRepository
     */
    public static function getRepository(Application $app)
    {
        return $app['orm.em']->getRepository('Todo\Entity\TaskEntity');
    }

    /**
     * @param int $id
     * @return null|object
     */
    public function findOneById(int $id)
    {
        return $this->find($id);
    }
}
