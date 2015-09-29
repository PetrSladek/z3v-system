<?php

namespace App\Presenters;

use App\Controls\ResultEditControl\ResultEditControl;
use App\Model\Pair;
use App\Model\Checkpoint;
use App\Query\PairsQuery;
use App\Services\Pairs;
use Nette\Application\UI\Multiplier;



class TimesPresenter extends BaseAuthPresenter
{

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

    	$this->template->pairs = $this->pairs->fetch($query);
        $this->template->checkpoints = $this->race->getCheckpoints();
    }


    public function createComponentResultEdit()
    {
        return new Multiplier(function ($pairId) {
            $pair = $this->em->getReference(Pair::class, $pairId);
            return new Multiplier(function($checkpointId) use ($pair) {
                $checkpoint = $this->em->getReference(Checkpoint::class, $checkpointId);
                return new ResultEditControl($pair, $checkpoint);
            });
        });
    }
    
}
