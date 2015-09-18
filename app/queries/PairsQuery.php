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
            $qb->innerJoin('p.members', 'm')
                ->addGroupBy('p.id')
                ->andHaving('SUM(m.paid) = COUNT(m.id)');
        };

        return $this;
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