<?php
/**
 * Created by PhpStorm.
 * User: Peggy
 * Date: 23.6.2015
 * Time: 21:44
 */

namespace App\Query;

use App\Model\Pair;
use App\Model\Race;
use App\Model\RacerParticipation;

use Doctrine\ORM\QueryBuilder;
use Kdyby\Persistence\Queryable;

/**
 * Class PairsQuery
 * @package App\Query
 * @see https://github.com/kdyby/doctrine/blob/master/docs/en/resultset.md
 */
class PairsQuery extends BaseQuery {



    /**
     * Najde podle závodu ve kterém je dvojice pøihlášená
     * @param $varSymbol
     * @return $this
     */
    public function fromRace(Race $race)
    {
        $this->filter[] = function (QueryBuilder $qb) use ($race) {
            $qb->andWhere('p.race = :race')
               ->setParameter('race', $race);
        };
        return $this;
    }


    public function onlyPaid() {

        $this->filter[] = function (QueryBuilder $qb) {

            // Neexistuje èlen, který by nezaplatil
            $sub = $qb->getEntityManager()->createQueryBuilder();
            $sub->select('rp.paid')
                ->from(RacerParticipation::class, 'rp')
                ->andWhere('rp.paid = 0')
                ->andWhere('rp.pair = p');
//                ->groupBy('rp.pair');

            $expr = $qb->expr()->not( $qb->expr()->exists($sub->getDQL()) );
            $qb->andWhere( $expr );

        };

        return $this;
    }


    public function onlyArrived()
    {
        $this->filter[] = function (QueryBuilder $qb) {
            $qb->andWhere('p.arrived = :arrived')
                ->setParameter('arrived', true);
        };
        return $this;
    }


    public function withMembers()
    {
        $this->filter[] = function (QueryBuilder $qb) {

            $qb->addSelect('m')
                ->addSelect('u')
                ->innerJoin('p.members', 'm')
                ->innerJoin('m.user', 'u');
        };


    }


    protected function createBasicDql(Queryable $repository)
    {
        $qb = $repository->createQueryBuilder()
            ->select('p') // pair
            ->from(Pair::class, 'p');

        $this->applyFilterTo($qb);

        return $qb;
    }

    /**
     * @param \Kdyby\Persistence\Queryable $repository
     * @return \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder
     */
    protected function doCreateQuery(Queryable $repository)
    {
        $qb = $this->createBasicDql($repository);
        $this->applySelectTo($qb);

        return $qb;
    }



    protected function doCreateCountQuery(Queryable $repository)
    {
        $qb = $this->createBasicDql($repository);
        $this->applySelectTo($qb);

        return $qb->select('COUNT(p.id)');
    }

}