<?php
/**
 * Created by PhpStorm.
 * User: Peggy
 * Date: 23.6.2015
 * Time: 21:44
 */

namespace App\Query;

use App\Model\Participation;
use App\Model\Race;
use App\Model\User;
use Doctrine\ORM\QueryBuilder;
use Kdyby\Persistence\Queryable;

/**
 * Class UsersQuery
 * @package App\Query
 * @see https://github.com/kdyby/doctrine/blob/master/docs/en/resultset.md
 */
class UsersQuery extends BaseQuery {



    /**
     * Najde uživatele podle závodu kterého se úèastní
     * @param Race $race
     * @return $this
     */
    public function participationOnRace(Race $race)
    {
        $this->filter[] = function (QueryBuilder $qb) use ($race) {
            $qb->innerJoin(Participation::class, 'p')
               ->andWhere('p.race = :race')
               ->setParameter('race', $race);
        };
        return $this;
    }

    /**
     * Najde uživatele podle závodu kterého se neúèastní
     * @param Race $race
     * @return $this
     */
    public function notParticipationOnRace(Race $race)
    {
        $this->filter[] = function (QueryBuilder $qb) use ($race) {

            $sbqb = $qb->getEntityManager()->createQueryBuilder();
            $sbqb->select()
                ->from(Participation::class, 'p')
                ->where('p.race = :race')
                ->andWhere('p.user = u')
                ->setParameter('race', $race);

            $qb->innerJoin(Participation::class, 'p')
                ->andWhere( $qb->expr()->not( $qb->expr()->exists($sbqb) ) )
                ->setParameter('race', $race);
        };
        return $this;
    }



    /**
     * @param Queryable $repository
     * @return \Kdyby\Doctrine\QueryBuilder
     */
    protected function createBasicDql(Queryable $repository)
    {
        $qb = $repository->createQueryBuilder()
            ->select('u') // user
            ->from(User::class, 'u');

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

        return $qb->select('COUNT(u.id)');
    }

}