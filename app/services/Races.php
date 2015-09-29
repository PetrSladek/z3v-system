<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Services;

use App\Model\Race;
use Kdyby\Doctrine\EntityManager;
use Nette\Caching\Cache;
use Nette\Object;

class Races extends Object
{
    private $em;
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Race::class);
    }




    /**
     * Zmeni uzamknuti zavodu
     * @param Race $race
     *
     * @throws \Exception
     */
    public function toggleLocked(Race $race)
    {
        $race->setLocked( !$race->isLocked() );
        $this->em->flush();
    }


    /**
     * Nastavi zavod jako aktualni
     * @param Race $race
     *
     * @throws \Exception
     */
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
        $race =  $this->repository->findOneBy(['actual'=>true]);
        return $race;
    }



    /**
     * Vrátí číslo dalšího stanoviště zadaného závodu
     * @param Race $race
     * @return int
     */
    public function getNextCheckpointNumber(Race $race)
    {
        $dql = $this->em->createQuery('SELECT MAX(ch.number)+1 FROM app:Checkpoint ch WHERE ch.race = :race');
        $dql->setParameter('race', $race);

        return (int) $dql->getSingleScalarResult();
    }


    /**
     * @deprecated use Query classes
     * @return array
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }


}