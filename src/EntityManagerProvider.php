<?php
declare(strict_types=1);

namespace Todo;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class EntityManagerProvider
{
    private static $em;

    /**
     * @return EntityManager
     */
    public static function getEntityManager()
    {
        if (!isset(self::$em))
        {
            self::$em = self::createEntityManager();
        }
        return self::$em;
    }

    protected static function createEntityManager()
    {
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__], true);
        $conn = [
            'driver' => 'pdo_mysql',
            'url' => 'mysql://todo:password@localhost/todo',
        ];
        $em = EntityManager::create($conn, $config);
        return $em;
    }
}
