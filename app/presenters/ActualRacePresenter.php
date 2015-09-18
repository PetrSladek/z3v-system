<?php

namespace App\Presenters;

use app\DynamicContainer;
use App\Forms\IPairAddFormFactory;
use App\Forms\IPairFormFactory;
use App\Model\Pair;
use App\Query\PairsQuery;
use App\Services\Pairs;
use Nette\Utils\Strings;


class ActualRacePresenter extends BaseAuthPresenter
{


    /**
     * @var IPairFormFactory
     * @inject
     */
    public $pairFormFactory;

    /**
     * @var IPairAddFormFactory
     * @inject
     */
    public $pairAddFormFactory;

    /**
     * @var Pairs
     * @inject
     */
    public $pairs;


    public function renderDefault()
    {
        $query = new PairsQuery();
        $query->fromRace($this->race);

    	$this->template->pairs = $this->pairs->fetch($query);
    }


    /**
     * Přetížení továrničky na komponenty, kvůli generování více formulářů v cyklu
     * @param $name
     * @return \App\Forms\PairForm|\Nette\ComponentModel\IComponent
     */
    protected function createComponent($name)
    {
        if($match = Strings::match($name, "/frmPair(\d+)/"))
        {
            return $this->createDynamicComponentFrmPair($match[1]);
        }
        else {
            return parent::createComponent($name);
        }

    }


    /**
     * Vytvoří formulář pro dvojici podle paramettru $id
     * @param $id
     * @return \App\Forms\PairForm
     */
    public function createDynamicComponentFrmPair($id)
    {
        $control = $this->pairFormFactory->create($id);
        $control->onSave[] = function($sender, Pair $entity)
        {
            $this->flashMessage("Dvojice #{$entity->getId()} úspěšně uložena", 'success');
            $this->redirect('this');
        };
        return $control;
    }


    /**
     * Formulář na přidání nové dvojice
     * @return \App\Forms\PairAddForm
     */
    public function createComponentFrmPairAdd()
    {
        $control = $this->pairAddFormFactory->create( $this->race );
        $control->onSave[] = function($sender, Pair $entity)
        {
            $this->flashMessage("Nová dvojice úspěšně vytvořena", 'success');
            $this->redirect('this');
        };
        return $control;
    }


    /**
     * Zruší dvojici a pošlle oboum notifikaci
     * @param $pairId
     */
    public function handleCancelPair($pairId)
    {
        try
        {
            /** @var Pair $pair */
            $pair = $this->em->find(Pair::class, $pairId);
            $this->pairs->cancelPair($pair, null);

            $this->flashMessage('Závodní dvojice byla z tohoto závodu úspěšně odebrána.', 'success');
        }
        catch (\RuntimeException $e)
        {
            // ostatni at klidne vyskoci, normalne to nemuze nastat
            $this->flashMessage($e->getMessage(), 'danger');
        }

        $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
    }
    
}
