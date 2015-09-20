<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Services;

use App\Model\Race;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

class Races extends Object
{
    private $em;
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Race::class);
        // $this->articles = $em->getRepository(App\Article::getClassName()); // for older PHP
    }


    public function toggleLocked(Race $race)
    {
        $race->setLocked( !$race->isLocked() );
        $this->em->flush();
    }


    public function setAsActual(Race $race)
    {
        $actual = $this->findActualRace();
        if($actual)
            $actual->setActual(false);

        $race->setActual(true);
        $this->em->flush();
    }


    /**
     * @return Race
     */
    public function findActualRace()
    {
        return $this->repository->findOneBy(['actual'=>true]);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }


}