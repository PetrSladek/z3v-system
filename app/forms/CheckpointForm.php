<?php

namespace App\Forms;

use App\Forms\Base\BaseModalForm;
use App\Model\Checkpoint;
use App\Model\Location;
use App\Model\Race;
use App\Services\Races;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Form;
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

    /**
     * @var Races
     */
    protected $races;


    public function __construct(EntityManager $em, HydratorInterface $hydrator, Races $races, Race $race, $id = null)
    {
        parent::__construct($em, $hydrator, $id);

        $this->races = $races;
        $this->race = $race;
    }


    /**
	 * @return Form
	 */
    protected function createComponentForm()
	{
		$frm = new Form;
		$frm->addText('number', 'Číslo:')
            ->setRequired()
            ->addRule($frm::INTEGER);
		$frm->addText('name', 'Název stanoviště:')
			->setRequired();
        $frm->addText('coefficient', 'Koeficient:')
            ->setRequired()
            ->setDefaultValue(1)
            ->addRule($frm::FLOAT);
        $frm->addText('location_lat', 'GPS Lat.')
            ->addCondition($frm::FILLED)
                ->addRule($frm::FLOAT);
        $frm->addText('location_lng', 'GPS Lng.')
            ->addCondition($frm::FILLED)
                ->addRule($frm::FLOAT);

		$frm->addSubmit('send', 'Uložit');

		$frm->onSuccess[] = [$this, 'formSuccess'];

		if ($this->entity)
        {
            $frm->setDefaults( $this->extract() );
        }
        else
        {
            $frm->setDefaults([
                'number' => $this->races->getNextCheckpointNumber($this->race)
            ]);
        }

		return $frm;
	}

    protected function extract()
    {
        $defaults = $this->hydrator->extract($this->entity);
        $defaults['location_lat'] = $this->entity->getLocation() ? $this->entity->getLocation()->getLat() : null;
        $defaults['location_lng'] = $this->entity->getLocation() ? $this->entity->getLocation()->getLng() : null;

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
        $values['location'] = new Location($values->location_lat, $values->location_lng);
        $this->hydrator->hydrate((array) $values, $this->entity);
    }


}


interface ICheckpointFormFactory
{
    /**
     * @param Race $race Závod
     * @param int|null $id
     * @return CheckpointForm
     */
	function create(Race $race, $id = null);
}