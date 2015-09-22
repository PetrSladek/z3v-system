<?php

namespace App\Presenters;

use app\DynamicContainer;
use App\Forms\ICheckpointFormFactory;
use App\Forms\IPairAddFormFactory;
use App\Forms\IPairFormFactory;
use App\Model\Checkpoint;
use App\Model\Pair;
use App\Query\PairsQuery;
use App\Services\Pairs;
use Nette\Utils\Strings;


class CheckpointsPresenter extends BaseAuthPresenter
{

    /**
     * @var ICheckpointFormFactory
     * @inject
     */
    public $checkpointFormFactory;

    /**
     * @var Checkpoint
     */
    private $entity;


    public function renderDefault()
    {
        $this->template->checkpoints = $this->race->getCheckpoints();
        $this->template->renderModal = !empty($this->template->renderModal) || false;
    }


    public function actionEdit($id = null)
    {

        if($id)
        {
            $this->entity = $this->em->getReference(Checkpoint::class, $id);
            if(!$this->entity)
                $this->error('Entity not found');
        }

        $this->redrawControl();
    }


    public function createComponentFrmCheckpoint()
    {
        $control = $this->checkpointFormFactory->create($this->race, $this->entity ? $this->entity->getId() : null);
        $control->onSave[] = function($sender, Checkpoint $entity) {
            if(!$this->entity)
                $this->flashMessage('Stanoviště úspěšně přidáno', 'success');
            else
                $this->flashMessage('Stanoviště úspěšně upraveno', 'success');

//            $this->isAjax() ? $this->redrawControl() :
            $this->redirect('default');
        };
        return $control;
    }



    public function handleRemove($id)
    {
        $this->entity = $this->em->getReference(Checkpoint::class, $id);
        if(!$this->entity)
            $this->error('Entity not found');

        $this->em->remove($this->entity);
        $this->em->flush();

        $this->flashMessage('Stanoviště smazáno');
        $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
    }

    
}
