<?php

namespace App\Forms;

use App\Forms\Base\BaseEntityForm;
use App\Model\Checkpoint;
use App\Model\Race;
use App\Forms\Base\Form;
use Nette\Utils\DateTime;


/**
 * Class RaceForm
 * @package App\Forms
 * @property Race|null $entity
 */
class RaceForm extends BaseEntityForm
{

    /**
     * @var string Class name of entity
     */
    protected $entityClass = Race::class;



    /**
	 * @return Form
	 */
    protected function createComponentForm()
	{
		$frm = new Form;
		$frm->addDatePicker('date', 'Datum')
            ->setRequired();
		$frm->addText('start_time', 'Čas startu');
        $frm->addText('location', 'Místo');
        $frm->addDatePicker('tshirt_end_date', 'Ukončení výběru triček');
        $frm->addTextArea('note', 'Poznámka');


		$frm->addSubmit('send', 'Uložit');

		$frm->onSuccess[] = [$this, 'formSuccess'];

		if($this->entity) {
            $frm->setDefaults( $this->extract() );
        }

		return $frm;
	}

    protected function extract() {
        $defaults = $this->hydrator->extract($this->entity);
        return $defaults;
    }

    /**
     * Nainstancuje novou entitu
     * @return mixed
     */
    protected function constructNewEntity($values)
    {
        return new Race($values->date);
    }

    protected function hydrate($values)
    {
        $values->start_time = new DateTime($values->start_time);
        $this->hydrator->hydrate((array) $values, $this->entity);
    }


}


interface IRaceFormFactory
{
	/** @return RaceForm */
	function create($id = null);
}