<?php

namespace Wmd\WatchMyDeskBundle\Manager;

use Doctrine\ORM\EntityManager;
use MineDoc\HomeBundle\Manager\BaseManager;
use MineDoc\HomeBundle\Entity\Commands;

class DeskManager extends BaseManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
    * Save Command entity
    *
    * @param Command $command 
    */
    public function saveCommand(Command $command)
    {
        $this->persistAndFlush($desk);
    }

    public function getRepository()
    {
        return $this->em->getRepository('MineDocHomeBundle:Commands');
    }
}