<?php
declare(strict_types=1);

namespace Todo\Application\User;

use Todo\EntityManagerProvider;
use Todo\Domain\User\Model\UserEntity;

class UserAppService
{
    /**
     * @param string $u_name
     * @param string $u_username
     * @param string $u_email
     * @param string $u_password
     * @return UserEntity
     */
    public static function saveUser(string $u_name, string $u_username, string $u_email, string $u_password)
    {
        $user = new UserEntity($u_name, $u_username, $u_email, $u_password);
        $em = EntityManagerProvider::getEntityManager();
        $em->persist($user);
        $em->flush();
        return $user;
    }
}
