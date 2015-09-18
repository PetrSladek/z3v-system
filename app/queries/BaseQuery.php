<?php
/**
 * Created by PhpStorm.
 * User: Peggy
 * Date: 23.6.2015
 * Time: 21:44
 */

namespace App\Query;

use Doctrine\ORM\QueryBuilder;
use Kdyby\Doctrine\QueryObject;

/**
 * Class BaseQuery
 * @package App\Query
 * @see https://github.com/kdyby/doctrine/blob/master/docs/en/resultset.md
 */
abstract class BaseQuery extends QueryObject {

    /**
     * @var array|\Closure[]
     */
    protected $filter = [];

    /**
     * @var array|\Closure[]
     */
    protected $select = [];


    protected function applySelectTo(QueryBuilder $qb)
    {
        foreach ($this->select as $modifier)
            $modifier($qb);
        return $qb;
    }


    protected function applyFilterTo(QueryBuilder $qb)
    {
        foreach ($this->filter as $modifier)
            $modifier($qb);
        return $qb;
    }

}