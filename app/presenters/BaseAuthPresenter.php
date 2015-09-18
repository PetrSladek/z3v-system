<?php

namespace App\Presenters;

use Kdyby\Doctrine\EntityManager;
use App\Model\User;
use Nette\InvalidStateException;


/**
 * Base presenter for all application presenters where user must be logged in.
 */
abstract class BaseAuthPresenter extends BasePresenter
{

    /**
     * @var EntityManager
     * @inject
     */
    public $em;

    protected function startup()
    {
        parent::startup();
        // pokud neni prihlaseny tak ho presmerujeme pryc
        if(!$this->getUser()->isLoggedIn()) {
            $this->flashMessage('Musíte se nejprve přihlásit.');
            $this->redirect('Sign:in');
        }

        // nactu entitu prihlaseneho uzivatele
        $this->me = $this->em->find(User::class, $this->getUser()->getId());
        if(!$this->me) {
            $this->getUser()->logout(true);
            $this->redirect('Sign:in');
        }


        // nactu entitu moji ucasti na tomto rocniku zavodu
        $this->participation = $this->me->getParticipationInRace($this->race);
    }


}
