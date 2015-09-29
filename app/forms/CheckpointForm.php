<?php

namespace App\Forms;

use App\Forms\Base\BaseModalForm;
use App\Model\Checkpoint;
use App\Model\Race;
use Kdyby\Doctrine\EntityManager;
use App\Forms\Base\Form;
use Zend\Stdlib\Hydrator\HydratorInterface;


/**
 * Class CheckpointForm
 * @package App\Forms
 * @property Checkpoint|null $entity
 */
class CheckpointForm extends BaseModalForm
{

    /**
     * @var string Class name of entity
     */
    protected $entityClass = Checkpoint::class;

    /**
     * @var string Soubor s šablonou
     */
    protected $templateFile = 'templates/checkpointForm.latte';

    /**
     * @var Race Závod do kterého se přidá stanoviště;
     */
    protected $race;


    public function __construct(EntityManager $em, HydratorInterface $hydrator, Race $race, $id = null)
    {
        parent::__construct($em, $hydrator, $id);

        $this->race = $race;
    }


    /**
	 * @return Form
	 */
    protected function createComponentForm()
	{
		$frm = new Form;
//        $frm->setAjax(true);

		$frm->addText('number', 'Číslo:')
            ->setRequired()
            ->addRule($frm::INTEGER);
		$frm->addText('name', 'Název stanoviště:')
			->setRequired();
        $frm->addText('coefficient', 'Koeficient:')
            ->setRequired()
            ->setDefaultValue(1)
            ->addRule($frm::FLOAT);

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
        return new Checkpoint($this->race, $values->number, $values->name);
    }

    protected function hydrate($values)
    {
        $this->hydrator->hydrate((array) $values, $this->entity);
    }


}


interface ICheckpointFormFactory
{
	/** @return CheckpointForm */
	function create(Race $race, $id = null);
}