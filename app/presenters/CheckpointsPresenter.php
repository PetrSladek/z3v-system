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
    private $checkpoint;


    public function renderDefault()
    {
        $this->template->checkpoints = $this->race->getCheckpoints();
//        $this->template->renderModal = !empty($this->template->renderModal) || false;
    }


//    public function actionEdit($id = null)
//    {
//
//        if($id)
//        {
//            $this->checkpoint = $this->em->getReference(Checkpoint::class, $id);
//            if(!$this->checkpoint)
//                $this->error('Entity not found');
//        }
//
//        $this->redrawControl();
//    }


    public function createComponentFrmCheckpoint()
    {
        $control = $this->checkpointFormFactory->create($this->race, $this->checkpoint ? $this->checkpoint->getId() : null);
        $control->onSave[] = function($sender, Checkpoint $entity)
        {
            $this->flashMessage("Stanoviště {$entity->getNumber()} - {$entity->getName()} úspěšně uloženo", 'success');
            $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
        };
        return $control;
    }



    public function handleRemove($id)
    {
        $this->checkpoint = $this->em->getReference(Checkpoint::class, $id);
        if(!$this->checkpoint)
            $this->error('Entity not found');

        $this->em->remove($this->checkpoint);
        $this->em->flush();

        $this->flashMessage('Stanoviště smazáno');
        $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
    }

    
}
