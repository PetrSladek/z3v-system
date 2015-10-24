<?php

namespace App\Presenters;

use App\Controls\IResultEditControlFactory;
use App\Model\Pair;
use App\Model\Checkpoint;
use App\Query\PairsQuery;
use App\Services\Pairs;
use Kdyby\Doctrine\ResultSet;
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

        /** @var ResultSet|Pair[] $pairs */
        $pairs = $this->pairs->fetch($query);
        /** @var Checkpoint[] $checkpoints */
        $checkpoints = $this->race->getCheckpoints();

        $pairs = $pairs->toArray();
        usort($pairs, function(Pair $a, Pair $b)
        {
            // najvíc stanovišť a nejlepší výsledný čas
            if ($a->getCountStartedCheckpoints() === $b->getCountStartedCheckpoints())
            {
                if ($a->getResultTime() === $b->getResultTime())
                    return 0;

                return $a->getResultTime() > $b->getResultTime() ? 1 : -1;
            }

            return  $a->getCountStartedCheckpoints() < $b->getCountStartedCheckpoints() ? 1 : -1;
        });

    	$this->template->pairs = $pairs;
        $this->template->checkpoints = $checkpoints;
    }

    
}
