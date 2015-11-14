<?php

namespace App\Presenters;

use App\Services\Notifications;
use Kdyby\Doctrine\EntityManager;
use Nette\Security\IUserStorage;


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

    /**
     * @var Notifications
     * @inject
     */
    public $notifications;


    /**
     * Pri spusteni kazdeho presenteru dediciho od BaseAuth
     */
    protected function startup()
    {
        parent::startup();
        // pokud neni prihlaseny tak ho presmerujeme pryc
        if (!$this->getUser()->isLoggedIn())
        {

            if ($this->getUser()->getLogoutReason() === IUserStorage::INACTIVITY)
            {
                $this->flashMessage('Byl jste z důvodu dlouhé neaktivity odhlášen. Přihlašte se prosím znovu.');
            }

            $this->redirect('Sign:in', array(
                'backlink' => $this->storeRequest()
            ));
        }

        if (!$this->getUser()->isAllowed($this->name, $this->action))
        {
            $this->flashMessage('Access denied');
            $this->redirect('Homepage:');
        }

        // nactu entitu prihlaseneho uzivatele
        $this->me = $this->getUser()->getIdentity();
    }

    protected function beforeRender()
    {
        parent::beforeRender();

        $this->template->notifications = $this->notifications->findUserLastNotifications($this->me);
    }


}
