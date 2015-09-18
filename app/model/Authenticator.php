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
		if (!$user) {
            throw new AuthenticationException('Tento email není zaregistrovaný.', self::IDENTITY_NOT_FOUND);
        } elseif($user->getPassword() === strtoupper(hash('sha256', $password))) { // stare heslo kvuli z5ne kompatibilitě
            $user->setPassword( $this->hash($password) );
		} elseif (!$this->verify($password, $user->getPassword())) {
			throw new AuthenticationException('Špatné heslo.', self::INVALID_CREDENTIAL);
		} elseif ($this->needsRehash($user->getPassword())) {
			$user->setPassword($this->hash($password));
		}

		$user->setLastLogin(new DateTime());
		$this->em->flush();

		// radsi vytahuju cerstvy data z DB vzdy na zacatku requestu (kvuli zmenam v administraci a tak)
		return $user->toIdentity();
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

