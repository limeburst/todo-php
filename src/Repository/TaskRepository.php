<?php
declare(strict_types=1);

namespace Todo\Repository;

use Doctrine\ORM\EntityRepository;

use Todo\EntityManagerProvider;
use Todo\Entity\TaskEntity;

class TaskRepository extends EntityRepository
{
    /**
     * @return TaskRepository
     */
    public static function getRepository()
    {
        $em = EntityManagerProvider::getEntityManager();
        return $em->getRepository('Todo\Entity\TaskEntity');
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
