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
use App\Services\Users;
use Nette\Utils\Strings;


class UsersPresenter extends BaseAuthPresenter
{

    /**
     * @var Users
     * @inject
     */
    public $users;


//    /**
//     * @var IRaceFormFactory
//     * @inject
//     */
//    public $raceFormFactory;


    public function renderDefault()
    {
        $users = $this->users->findAll();
        $this->template->users = $users;
        $this->template->total = count($users);

    }


//    /**
//     * @return RaceForm
//     */
//    protected function createComponentFrmRace()
//    {
//        $control = $this->raceFormFactory->create();
//        $control->onSave[] = function() {
//            $this->flashMessage('Nový ročník závodu úspěšně přidán', 'success');
//            $this->redirect('this');
//        };
//        return $control;
//    }
//
//
//    public function handleToggleLocked($id)
//    {
//        /** @var Race $race */
//        $race = $this->em->getReference(Race::class, $id);
//        $this->races->toggleLocked($race);
//
//        $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
//    }
//
//    public function handleSetActual($id)
//    {
//        /** @var Race $race */
//        $race = $this->em->getReference(Race::class, $id);
//        $this->races->setAsActual($race);
//
//        $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
//    }



    
}
