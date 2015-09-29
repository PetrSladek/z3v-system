<?php

namespace App\Presenters;

use app\DynamicContainer;
use App\Forms\ICheckpointFormFactory;
use App\Forms\IPairAddFormFactory;
use App\Forms\IPairFormFactory;
use App\Forms\IRaceFormFactory;
use App\Forms\RaceForm;
use App\Model\Checkpoint;
use App\Model\Pair;
use App\Model\Race;
use App\Query\PairsQuery;
use App\Services\Pairs;
use App\Services\Races;
use Nette\Utils\Strings;


class RacesPresenter extends BaseAuthPresenter
{

    /**
     * @var Races
     * @inject
     */
    public $races;


    /**
     * @var IRaceFormFactory
     * @inject
     */
    public $raceFormFactory;


    public function renderDefault()
    {
        $this->template->races = $this->races->findAll();

    }


    /**
     * @return RaceForm
     */
    protected function createComponentFrmRace()
    {
        $control = $this->raceFormFactory->create();
        $control->onSave[] = function($sender, Race $race)
        {
            $this->flashMessage("Závod {$race->getYear()} {$race->getLocation()} úspěšně uložen", 'success');
            $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
        };
        return $control;
    }


    public function handleToggleLocked($id)
    {
        /** @var Race $race */
        $race = $this->em->getReference(Race::class, $id);
        $this->races->toggleLocked($race);

        $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
    }

    public function handleSetActual($id)
    {
        /** @var Race $race */
        $race = $this->em->getReference(Race::class, $id);
        $this->races->setAsActual($race);

        $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
    }



    
}
