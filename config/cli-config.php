<?php
declare(strict_types=1);

use Todo\EntityManagerProvider;

$em = EntityManagerProvider::getEntityManager();

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($em);
