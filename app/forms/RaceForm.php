<?php

namespace App\Forms;

use App\Forms\Base\BaseModalForm;
use App\Model\Checkpoint;
use App\Model\Race;
use App\Forms\Base\Form;
use Nette\Utils\DateTime;


/**
 * Class RaceForm
 * @package App\Forms
 * @property Race|null $entity
 */
class RaceForm extends BaseModalForm
{

    /**
     * @var string Class name of entity
     */
    protected $entityClass = Race::class;

    /**
     * @var string Soubor s šablonou
     */
    protected $templateFile = 'templates/raceForm.latte';



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

    protected function extract()
    {
        $defaults = $this->hydrator->extract($this->entity);
        $defaults['start_time'] = $this->entity->getStartTime()->format("H:i");
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