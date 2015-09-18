<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


class SignFormFactory extends Nette\Object
{
	/** @var User */
	private $user;


	public function __construct(User $user)
	{
		$this->user = $user;
	}


	/**
	 * @return Form
	 */
	public function create()
	{
		$form = new Form;
		$form->addText('email', 'E-mail:')
			->setRequired();
		$form->addPassword('password', 'Heslo:')
			->setRequired();

//		$form->addCheckbox('remember', 'Keep me signed in');

		$form->addSubmit('send', 'Přihlásit se');

		$form->onSuccess[] = array($this, 'formSucceeded');
		return $form;
	}


	public function formSucceeded(Form $form, $values)
	{
//		if ($values->remember) {
//			$this->user->setExpiration('14 days', FALSE);
//		} else {
//			$this->user->setExpiration('20 minutes', TRUE);
//		}
		$this->user->setExpiration('14 days', false);

		try {
			$this->user->login($values->email, $values->password);
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}

}
