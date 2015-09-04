<?php

namespace App\Forms;

use App\Forms\Base\BaseEntityForm;
use App\Model\Address;
use App\Model\Authenticator;
use App\Model\User;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\BaseControl;
use Zend\Stdlib\Hydrator\HydratorInterface;


class UserRegistrationForm extends BaseEntityForm
{


    /**
     * @var User|null
     */
    protected $entity;

    /**
     * @var string Class name of entity
     */
    protected $entityClass = User::class;

    /**
     * @var Authenticator
     */
    protected $authenticator;


    public function __construct(EntityManager $em, HydratorInterface $hydrator, Authenticator $authenticator)
    {
        parent::__construct($em,$hydrator,null);
        $this->authenticator = $authenticator;
    }

	/**
	 * @return Form
	 */
    protected function createComponentForm()
	{
		$frm = new Form;

        $frm->addGroup('Základní údaje');
		$frm->addText('name', 'Jméno:')
            ->setRequired();
		$frm->addText('surname', 'Příjmení:')
			->setRequired();
        $frm->addDatePicker('birthdate', 'Datum narození')
            ->setRequired();;
        $frm->addText('nickname', 'Přezdívka:');

        $frm->addGroup('Přihlašovací údaje');
		$frm->addText('email', 'E-mail:')
            ->addRule($frm::EMAIL)
            ->addRule(function(BaseControl $control) use($frm) {
                /** @var User $user */
                $user = $this->em->getRepository(User::class)->findOneBy(['email'=>$control->value]);
                // Pokud existuje a sedi i heslo, tak ho přihlásit a aktualizovat mu údaje
                if($user && $this->authenticator->verify($frm['password']->value, $user->getPassword())) {
                    $this->entity = $user;
                    return true;
                }
                return $user === null; // pokud existuje nekdo jinej s timto emailemm, tak je to spatne
            }, 'Tento e-mail je už zaregistrovaný, zvolte jiný, nebo se pomocí něj přihlašte')
			->setRequired();
        $frm->addPassword('password', 'Heslo:')
            ->setRequired();
        $frm->addPassword('password_again', 'Heslo znovu:')
            ->setOmitted()
            ->setRequired()
            ->addRule($frm::EQUAL, 'Hesla musí být stejná', $frm['password']);

        $frm->addGroup('Adresa');
        $cnt = $frm->addContainer('address');
        $cnt->addText('street', 'Ulice a čp:')
            ->setRequired();
        $cnt->addText('city', 'Město:')
            ->setRequired();
        $cnt->addText('postal_code', 'PSČ:')
            ->setRequired();
        $cnt->addText('country', 'Země:')
            ->setRequired()
            ->setDefaultValue('Česká republika');


        $frm->addGroup('Ostatní');
        $frm->addText('phone', 'Telefon:')
            ->setRequired();
        $frm->addText('tshirt', 'Triko:');
        $frm->addTextArea('note', 'Poznámka:');

        $frm->setCurrentGroup(null);
		$frm->addSubmit('send', 'Uložit');

		$frm->onSuccess[] = [$this, 'formSuccess'];


		return $frm;
	}

    protected function extract() {}

    protected function hydrate($values) {

        // naplň datama adresu
        $address = new Address();
        $this->hydrator->hydrate((array) $values->address, $address);
        $values->address = $address;

        // naplň datama zbytek
        $this->hydrator->hydrate((array) $values, $this->entity);

        // vyplň heslo
        $this->entity->setPassword( $this->authenticator->hash($values->password) );
    }



}


interface IUserRegistrationFormFactory
{
	/** @return UserRegistrationForm */
	function create();
}