<?php

namespace App\Presenters;

use App\Forms\IUserRegistrationFormFactory;
use App\Model\User;
use Nette;
use App\Forms\SignFormFactory;


class SignPresenter extends BasePresenter
{

    /** @persistent */
    public $backlink;

	/** @var SignFormFactory @inject */
	public $signInFormFactory;

    /** @var IUserRegistrationFormFactory @inject */
    public $userRegistrationFormFactory;


	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentFrmSignIn()
	{
		$form = $this->signInFormFactory->create();
		$form->onSuccess[] = function ($form) {
            $this->restoreRequest($this->backlink);
			$this->redirect('Homepage:');
		};
		return $form;
	}


    /**
     * Registration form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentFrmRegistration()
    {
        $form = $this->userRegistrationFormFactory->create();
        $form->onSave[] = function ($control,User $entity) {
            $this->getUser()->login( $entity->toIdentity() );
            $this->flashMessage('Byl jste úspěšně zaregistrován.');
            $this->redirect('Homepage:');
        };
        return $form;
    }


    /**
     * Odhlášení
     */
	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('Byli jste úspěšně odhlášení.');
		$this->redirect('in');
	}

}
