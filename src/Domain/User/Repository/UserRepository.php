<?php
declare(strict_types=1);

namespace Todo\Domain\User\Repository;

use Doctrine\ORM\EntityRepository;

use Todo\EntityManagerProvider;
use Todo\Domain\User\Model\UserEntity;

class UserRepository extends EntityRepository
{
    /**
     * @return UserRepository
     */
    public static function getRepository()
    {
        $em = EntityManagerProvider::getEntityManager();
        return $em->getRepository('Todo\Domain\User\Model\UserEntity');
    }

    /**
     * @param int $id
     * @return null|UserEntity
     */
    public function findOneById(int $id)
    {
        return $this->find($id);
    }

    /**
     * @param string $username
     * @return null|UserEntity
     */
    public function findOneByUsername(string $username)
    {
        return $this->findOneBy(['username' => $username]);
    }
}
