<?php

namespace App\Presenters;

use App\Forms\IUserFormFactory;
use App\Forms\UserForm;
use App\Model\User;
use App\Query\UsersQuery;
use App\Services\Users;
use Kdyby\Doctrine\ResultSet;

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


    /**
     * @var ResultSet|User[]
     */
    private $list;


    public function actionDefault()
    {
    }

    public function renderDefault()
    {
        $this->list = $this->list ?: $this->users->fetch( new UsersQuery() );
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
