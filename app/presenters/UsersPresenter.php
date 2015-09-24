<?php

namespace App\Presenters;

use app\DynamicContainer;
use App\Forms\Base\Form;
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
use Nette\Forms\Controls\BaseControl;
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

    /**
     * @var User[]
     */
    private $list;

    public function actionDefault()
    {
    }

    public function renderDefault()
    {
        $this->list = $this->list ?: $this->users->findAll();
        $this->template->users = $this->list;
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
            $this->list = [$entity];
            $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
        };
        return $control;
    }




    
}
