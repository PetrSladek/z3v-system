<?php

namespace App\Forms;

use App\Model\Notification;
use App\Model\Race;
use App\Model\User;
use Doctrine\ORM\EntityNotFoundException;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\BaseControl;


class PairSendInvitationForm extends Control
{

    /** @var callable[]  function (UserForm $sender, User $entity); */
    public $onSave;

    /**
     * @var Race
     */
    protected $actualRace;

    /**
     * @var User
     */
    protected $me;

    /**
     * @var EntityManager
     */
    protected $em;


    public function __construct(EntityManager $em, User $me, Race $actualRace)
    {
        parent::__construct();

        $this->em = $em;
        $this->me = $me;
        $this->actualRace = $actualRace;
    }

    public function render() {
        $this['form']->render();
    }

	/**
	 * @return Form
	 */
    protected function createComponentForm()
	{
		$frm = new Form;
		$frm->addText('email', 'Zaslat pozvánku registrovanému závodníkovi na email:')
			->setRequired()
            ->addRule($frm::EMAIL)
            ->addRule(function(BaseControl $control) {
                $recipient = $this->em->getRepository(User::class)->findOneBy(['email'=>$control->value]);
                return $recipient !== null;
            }, 'Tento uživatel neexistuje')
            ->addRule(function(BaseControl $control) {
                return $control->value !== $this->me->getEmail();
            }, 'Nemůžeš poslat pozvánku sám sobě!');

		$frm->addSubmit('send', 'Odeslat');

		$frm->onSuccess[] = [$this, 'formSuccess'];

		return $frm;
	}


    public function formSuccess(Form $form, $values)
    {
        /** @var User|null $recipient */
        $recipient = $this->em->getRepository(User::class)->findOneBy(['email'=>$values->email]);
        if(!$recipient)
            throw new EntityNotFoundException("User with email {$values->email} not found");

        $notification = new Notification($this->actualRace, $this->me, $recipient, Notification::TYPE_INVITATION);

        $this->em->persist($notification);
        $this->em->flush();

        $this->onSave($this, $notification);
    }



}


interface IPairSendInvitationFormFactory
{
	/**
     * @param $me Odesílatel pozvánky
     * @param $actualRace Aktuální ročník závodu ve kterém je pozvánka platná
     * @return PairSendInvitationForm
     */
	function create(User $me, Race $actualRace);
}