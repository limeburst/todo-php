<?php
declare(strict_types=1);

namespace Todo\Domain\Task\Repository;

use Todo\BaseEntityRepository;
use Todo\EntityManagerProvider;
use Todo\Domain\Task\Model\TaskEntity;

class TaskRepository extends BaseEntityRepository
{
    /**
     * @return TaskRepository
     */
    public static function getRepository()
    {
        $em = EntityManagerProvider::getEntityManager();
        return $em->getRepository('Todo\Domain\Task\Model\TaskEntity');
    }

    /**
     * @param int $id
     * @return null|TaskEntity
     */
    public function findOneById(int $id)
    {
        return $this->find($id);
    }
}
