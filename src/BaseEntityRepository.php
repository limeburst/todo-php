<?php
declare(strict_types=1);

namespace Todo;

use Doctrine\ORM\EntityRepository;

class BaseEntityRepository extends EntityRepository
{
    public function save($entity)
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush($entity);
    }
}
