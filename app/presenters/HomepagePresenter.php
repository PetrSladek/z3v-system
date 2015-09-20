<?php

namespace App\Presenters;

use App\Forms\IPairSendInvitationFormFactory;
use App\Forms\IUserFormFactory;
use App\Forms\IUserPasswordFormFactory;
use App\Forms\UserForm;
use App\Forms\UserPasswordForm;
use App\Model\Notification;
use App\Model\User;
use App\Services\Notifications;
use Nette\Neon\Exception;


class HomepagePresenter extends BaseAuthPresenter
{

	/**
	 * @var IUserFormFactory
	 * @inject
	 */
	public $userFormFacotry;

    /**
     * @var IUserPasswordFormFactory
     * @inject
     */
    public $userPasswordFormFactory;

    /**
     * @var IPairSendInvitationFormFactory
     * @inject
     */
    public $pairSendInvitationFormFactory;


    /**
     * @var Notifications
     * @inject
     */
    public $notifications;

    /**
     * @var \Inflection
     * @inject
     */
    public $inflection;


	public function renderDefault()
	{
		$this->template->notifications = $this->notifications->findUserLastNotifications($this->me);
        $this->template->unreadNotifications = $this->notifications->findUserUnreadNotifications($this->me);
	}

	/**
	 * @return UserForm
	 */
	protected function createComponentFrmUser()
	{
		$control = $this->userFormFacotry->create( $this->me->getId() );
        $control->onSave[] = function($sender, User $entity) {
            $this->flashMessage('Údaje úspěšně uloženy', 'success');
            $this->redirect('this');
        };
        return $control;
	}

    /**
     * @return UserPasswordForm
     */
    protected function createComponentFrmUserPassword()
    {
        $control = $this->userPasswordFormFactory->create( $this->me->getId() );
        $control->onSave[] = function($sender, User $entity) {
            $this->flashMessage('Heslo úspěšně změněno', 'success');
            $this->redirect('this');
        };
        return $control;
    }

    /**
     * @return UserPasswordForm
     */
    protected function createComponentFrmPairSendInvitation()
    {
        $control = $this->pairSendInvitationFormFactory->create( $this->me, $this->race );
        $control->onSave[] = function($sender, Notification $entity) {
            $target = $entity->getRecipient()->getFullName();
            $target = $this->inflection->inflect($target);
            $target = $target[3]; // komu - cemu
            $this->flashMessage(sprintf('Pozvánka byla úspěšně odeslána %s na email %s', $target, $entity->getRecipient()->getEmail()), 'success');
            $this->redirect('this');
        };
        return $control;
    }


    public function handleAcceptInvitation($notificationId) {
        try {
            $notification = $this->notifications->getById($notificationId);
            $this->notifications->acceptInvitation($this->me, $notification);
            $this->flashMessage('Pozvání bylo přijato, po zaplacení startovného budete úspěšně přihlášeni do závodu.', 'success');

        } catch (\RuntimeException $e) { // ostatni at klidne vyskoci, normalne to nemuze nastat
            $this->flashMessage($e->getMessage(), 'danger');
        }

        $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
    }


    public function handleRejectInvitation($notificationId) {
        try {
            $notification = $this->notifications->getById($notificationId);

            $this->notifications->rejectInvitation($this->me, $notification);
            $this->flashMessage('Pozvání bylo odmítnuto', 'success');

        } catch (\RuntimeException $e) { // ostatni at klidne vyskoci, normalne to nemuze nastat
            $this->flashMessage($e->getMessage(), 'danger');
        }

        $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
    }

    public function handleMarkAsRead($notificationId) {

        $notification = $this->notifications->getById($notificationId);
        $notification->markAsRead();
        $this->em->flush();

        $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
    }
}
