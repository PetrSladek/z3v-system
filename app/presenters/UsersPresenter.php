<?php

namespace App\Presenters;

use app\DynamicContainer;
use App\Forms\ICheckpointFormFactory;
use App\Forms\IPairAddFormFactory;
use App\Forms\IPairFormFactory;
use App\Forms\IRaceFormFactory;
use App\Forms\IUserFormFactory;
use App\Forms\RaceForm;
use App\Forms\UserForm;
use App\Model\Checkpoint;
use App\Model\Pair;
use App\Model\Race;
use App\Model\User;
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

    /**
     * @var IUserFormFactory
     * @inject
     */
    public $userFormFactory;


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


    /**
     * @return UserForm
     */
    public function createComponentFrmUser()
    {
        $control = $this->userFormFactory->create(null);
        $control->onSave[] = function($sender, User $entity)
        {
            $this->flashMessage("Uživatel {$entity->getFullNameWithNickname()} úspěšně uložen", 'success');
            $this->redirect('this');
        };
        return $control;
    }



    
}
