<?php

namespace App\Forms;

use App\Forms\Base\BaseEntityForm;
use App\Model\Address;
use App\Model\User;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\BaseControl;


/**
 * Class UserForm
 * @package App\Forms
 * @property $entity User|null
 */
class UserForm extends BaseEntityForm
{

    /**
     * @var string Class name of entity
     */
    protected $entityClass = User::class;


	/**
	 * @return Form
	 */
    protected function createComponentForm()
	{
		$frm = new Form;
		$frm->addText('name', 'Jméno:')
            ->setRequired();
		$frm->addText('surname', 'Příjmení:')
			->setRequired();
        $frm->addDatePicker('birthdate', 'Datum narození')
            ->setRequired();;
        $frm->addText('nickname', 'Přezdívka:');
		$frm->addText('email', 'E-mail:')
            ->addRule($frm::EMAIL)
            ->addRule(function(BaseControl $control) {
                if($this->entity && $this->entity->getEmail() == $control->value)
                    return true; // email neměním takze ok
                $user = $this->em->getRepository(User::class)->findOneBy(['email'=>$control->value]);
                return $user === null; // pokud existuje nekdo jinej s timto emailemm, tak je to spatne
            }, 'Tento e-mail je už zaregistrovaný, zvolte jiný')
			->setRequired();

        $cnt = $frm->addContainer('address');
        $cnt->addText('street', 'Ulice a čp:');
        $cnt->addText('city', 'Město:');
        $cnt->addText('postal_code', 'PSČ:');
        $cnt->addText('country', 'Země:')
            ->setDefaultValue('Česká republika');

        $frm->addText('phone', 'Telefon:')
            ->setRequired();


        $frm->addText('tshirt', 'Triko:');
        $frm->addTextArea('note', 'Poznámka:');

		$frm->addSubmit('send', 'Uložit');

		$frm->onSuccess[] = [$this, 'formSuccess'];

		if($this->entity) {
            $frm->setDefaults( $this->extract() );
        }

		return $frm;
	}

    protected function extract() {
        $defaults = $this->hydrator->extract($this->entity);
        $defaults['address'] = $this->hydrator->extract($this->entity->getAddress());
//        $defaults['birthdate'] = $this->entity->getBirthdate()->format('j.n.Y');

        return $defaults;
    }

    protected function hydrate($values) {

        // naplň datama adresu
        $address = new Address();
        $this->hydrator->hydrate((array) $values->address, $address);
        $values->address = $address;

        // naplň datama zbytek
        $this->hydrator->hydrate((array) $values, $this->entity);
    }


}


interface IUserFormFactory
{
	/** @return UserForm */
	function create($id);
}