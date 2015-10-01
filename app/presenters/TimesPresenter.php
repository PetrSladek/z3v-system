<?php

namespace App\Presenters;

use App\Controls\IResultEditControlFactory;
use App\Model\Pair;
use App\Model\Checkpoint;
use App\Query\PairsQuery;
use App\Services\Pairs;
use Nette\Application\UI\Multiplier;



class TimesPresenter extends BaseAuthPresenter
{

    /**
     * @var IResultEditControlFactory
     * @inject
     */
    public $resultEditControlFactory;

    /**
     * @var Pairs
     * @inject
     */
    public $pairs;

    public function renderDefault()
    {
        $query = new PairsQuery();
        $query->fromRace($this->race);
        $query->withMembers();
        $query->onlyArrived();
        $query->withResults();

    	$this->template->pairs = $this->pairs->fetch($query);
        $this->template->checkpoints = $this->race->getCheckpoints();
    }


    public function createComponentResultEdit()
    {
        return new Multiplier(function ($pairId)
        {
            $pair = $this->em->getReference(Pair::class, $pairId);
            return new Multiplier(function($checkpointId) use ($pair)
            {
                $checkpoint = $this->em->getReference(Checkpoint::class, $checkpointId);

                $control = $this->resultEditControlFactory->create($pair, $checkpoint);
                $control->onSave[] = function($sender, $result) use ($pair)
                {
                    $this->em->persist($pair)->flush();

                    $this->flashMessage('Výsledky úspěšně uloženy');
                    $this->isAjax() ? $this->redrawControl() : $this->redirect('this');
                };
                return $control;
            });
        });
    }
    
}
