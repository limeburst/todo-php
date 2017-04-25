<?php
declare(strict_types=1);

namespace Todo\Repository;

use Doctrine\ORM\EntityRepository;
use Silex\Application;

class UserRepository extends EntityRepository
{
    /**
     * @param Application $app
     * @return UserRepository
     */
    public static function getRepository(Application $app)
    {
        return $app['orm.em']->getRepository('Todo\Entity\UserEntity');
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
