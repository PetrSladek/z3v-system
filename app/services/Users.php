<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Services;

use App\Model\User;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

class Users extends Object
{
    private $em;
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(User::class);
    }


    public function findAll()
    {
        return $this->repository->findAll();
    }


}