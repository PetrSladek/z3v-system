<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Services;

use App\Model\Authenticator;
use App\Model\DuplicateEmailException;
use App\Model\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

class Users extends Object
{
    private $em;
    private $repository;
    private $authenticator;

    public function __construct(EntityManager $em, Authenticator $authenticator)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(User::class);
        $this->authenticator = $authenticator;
    }


    public function findAll()
    {
        $dql = $this->em->createQuery('SELECT u FROM app:User u');
        return $dql->iterate();
    }

    public function createUser($email, $password)
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->authenticator->hash($password));

        try
        {
            $this->em->persist($user)->flush();
        }
        catch (UniqueConstraintViolationException $e)
        {
            throw new DuplicateEmailException;
        }

    }

}