<?php

namespace App\Forms;

use App\Forms\Base\BaseEntityForm;
use App\Forms\Base\Form;
use App\Model\Address;
use App\Model\User;
use Doctrine\ORM\EntityNotFoundException;
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
     * Vykreslit otevřený modal?
     * @var bool
     * @persistent
     */
    public $renderModal = false;

    /**
     * @var int
     * @persistent
     */
    public $id;

    protected function attached($presenter)
    {
        parent::attached($presenter);

        if($this->id)
            $this->load($this->id);
    }

    public function load($id)
    {
        $this->entity = $this->em->find($this->entityClass, $id);
        if(!$this->entity)
            throw new EntityNotFoundException;
    }


    /**
     * Vykreslení formu do modalu
     */
    public function render()
    {
        $this->template->setFile(__DIR__.'/templates/userForm.latte');
        $this->template->renderModal = $this->renderModal;
        $this->template->entity = $this->entity;

        $this->redrawControl();
        $this->template->render();
    }

    /**
     * Otevře modal a do něj entitu podle ID
     * @param $id
     * @throws EntityNotFoundException
     */
    public function handleOpen($id = null)
    {
        if($id)
            $this->load($id);

        $this->renderModal = true;
        $this->redrawControl();
    }


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
                // email neměním takze ok
                if($this->entity && $this->entity->getEmail() == $control->value)
                    return true;
                // pokud existuje nekdo jinej s timto emailemm, tak je to spatne
                $user = $this->em->getRepository(User::class)->findOneBy(['email'=>$control->value]);
                return $user === null;
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

        $frm->onSuccess[] = function()
        {
            $this->presenter->payload->modalClose = true;
            $this->renderModal = false;
        };
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