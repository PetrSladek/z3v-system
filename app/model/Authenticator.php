<?php

namespace App\Model;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine\EntityManager;
use Latte\Object;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\Passwords;
use Nette\Utils\DateTime;


class Authenticator extends Object implements IAuthenticator
{


	/** @var EntityManager */
	private $em;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

    public function hash($password) {
        return Passwords::hash($password);
    }

    public function verify($password, $hash) {
        return Passwords::verify($password, $hash);
    }

    public function needsRehash($password) {
        return Passwords::needsRehash($password);
    }

	/**
	 * Performs an authentication.
	 * @return Identity
	 * @throws AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;

		$repo = $this->em->getRepository(User::class);

        /** @var User $user */
		$user = $repo->findOneBy(['email'=>$email]);
        // neexistující email
		if (!$user)
        {
            throw new AuthenticationException('Tento email není zaregistrovaný.', self::IDENTITY_NOT_FOUND);
        }
        // stare heslo kvuli z5ne kompatibilitě
        elseif($user->getPassword() === strtoupper(hash('sha256', $password)))
        {
            $user->setPassword( $this->hash($password) );
		}
        // Špatné heslo
        elseif (!$this->verify($password, $user->getPassword()))
        {
			throw new AuthenticationException('Špatné heslo.', self::INVALID_CREDENTIAL);
		}
        // je potřeba heslo přehashovat
        elseif ($this->needsRehash($user->getPassword()))
        {
			$user->setPassword($this->hash($password));
		}

		$user->setLastLogin(new DateTime());
		$this->em->flush();

        // user implementuje IIdentity
		return $user;
	}


	/**
	 * Adds new user.
	 * @param  string
	 * @param  string
	 * @return void
	 */
	public function add($email, $password)
	{
		try {

			$user = new User();
			$user->setEmail($email);
			$user->setPassword( $this->hash($password) );

			$this->em->persist($user);
			$this->em->flush();


		} catch (UniqueConstraintViolationException $e) {
			throw new DuplicateEmailException;
		}
	}

}

