<?php

namespace MineDoc\HomeBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CommandsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommandsRepository extends EntityRepository
{
    public function getCommands()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM MineDocHomeBundle:Commands c')
            ->getResult();
    }
}