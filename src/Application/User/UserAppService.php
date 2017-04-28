<?php
declare(strict_types=1);

namespace Todo\Application\User;

use Todo\Domain\User\Repository\UserRepository;
use Todo\Domain\User\Model\UserEntity;

class UserAppService
{
    /**
     * @param string $name
     * @param string $username
     * @param string $email
     * @param string $password
     * @return UserEntity
     */
    public static function saveUser(string $name, string $username, string $email, string $password)
    {
        $user = new UserEntity($name, $username, $email, $password);
        UserRepository::getRepository()->save($user);
        return $user;
    }
}
