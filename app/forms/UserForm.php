<?php

namespace App\Forms;

use App\Forms\Base\BaseEntityForm;
use App\Forms\Base\Form;
use App\Model\Address;
use App\Model\User;
use Nette\Forms\Controls\BaseControl;


/**
 * Class UserForm
 * @package App\Forms
 * @property User|null $entity
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
		$frm = new Form();
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

//        $frm->addTextArea('note', 'Poznámka:');

		$frm->addSubmit('send', 'Uložit');

		$frm->onSuccess[] = [$this, 'formSuccess'];

		if($this->entity)
        {
            $frm->setDefaults( $this->extract() );
        }

		return $frm;
	}

    protected function extract() {
        $defaults = [];
        $defaults['name'] = $this->entity->getName();
        $defaults['surname'] = $this->entity->getSurname();
        $defaults['birthdate'] = $this->entity->getBirthdate();
        $defaults['nickname'] = $this->entity->getNickname();
        $defaults['email'] = $this->entity->getEmail();
        $defaults['address']['street'] = $this->entity->getAddress()->getStreet();
        $defaults['address']['city'] = $this->entity->getAddress()->getCity();
        $defaults['address']['postal_code'] = $this->entity->getAddress()->getPostalCode();
        $defaults['address']['country'] = $this->entity->getAddress()->getCountry();
        $defaults['phone'] = $this->entity->getPhone();
        $defaults['tshirt'] = $this->entity->getTshirt();

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