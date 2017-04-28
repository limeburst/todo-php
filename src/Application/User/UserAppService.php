<?php
declare(strict_types=1);

namespace Todo\Application\User;

use Todo\Domain\User\Model\UserEntity;
use Todo\Domain\User\Repository\UserRepository;

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

    /**
     * @param int $id
     * @return null|UserEntity
     */
    public static function getUserById(int $id)
    {
        $user = UserRepository::getRepository()->findOneById($id);
        return $user;
    }

    /**
     * @param string $username
     * @return null|UserEntity
     */
    public static function getUserByUsername(string $username)
    {
        $user = UserRepository::getRepository()->findOneByUsername($username);
        return $user;
    }

    /**
     * @return array
     */
    public static function getAllUsers()
    {
        $users = UserRepository::getRepository()->findAll();
        return $users;
    }
}
