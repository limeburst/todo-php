<?php
declare(strict_types=1);

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once __DIR__.'/../vendor/autoload.php';

$config = Setup::createAnnotationMetadataConfiguration([__DIR__.'/../src'], true);
$conn = [
	'driver' => 'pdo_mysql',
	'url' => 'mysql://todo:password@localhost/todo',
];
$em = EntityManager::create($conn, $config);

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($em);
