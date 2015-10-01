<?php

namespace App\Presenters;

use App\Controls\IResultEditControlFactory;
use App\Model\Pair;
use App\Model\Checkpoint;
use App\Query\PairsQuery;
use App\Services\Pairs;
use Nette\Application\UI\Multiplier;



class ResultsPresenter extends BaseAuthPresenter
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
        $query->withResults();

        /** @var Pair[] $pairs */
        $pairs = $this->pairs->fetch($query);
        /** @var Checkpoint[] $checkpoints */
        $checkpoints = $this->race->getCheckpoints();

    	$this->template->pairs = $pairs;
        $this->template->checkpoints = $checkpoints;
    }

    
}
