<?php

namespace App\Presenters;

use App\Query\PairsQuery;
use App\Services\Pairs;
use Tracy\Debugger;
use Tracy\Dumper;


class PairsPresenter extends BaseAuthPresenter
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
        $query->onlyPaid();
//
//        Debugger::$maxDepth = 5;
//        dump( $this->pairs->fetch($query)->toArray() );
//        die;

    	$this->template->pairs = $this->pairs->fetch($query);
//        dump( $this->template->pairs[0]->getFirstMember() ); die;
    }
    
}
