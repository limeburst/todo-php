<?php
declare(strict_types=1);

namespace Todo\Repository;

use Doctrine\ORM\EntityRepository;

use Todo\EntityManagerProvider;
use Todo\Entity\UserEntity;

class UserRepository extends EntityRepository
{
    /**
     * @return UserRepository
     */
    public static function getRepository()
    {
        $em = EntityManagerProvider::getEntityManager();
        return $em->getRepository('Todo\Entity\UserEntity');
    }

    /**
     * @param int $id
     * @return null|object
     */
    public function findOneById(int $id)
    {
        return $this->find($id);
    }

    /**
     * @param string $username
     * @return null|object
     */
    public function findOneByUsername(string $username)
    {
        return $this->findOneBy(['username' => $username]);
    }
}
