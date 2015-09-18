<?php

namespace App\Presenters;

use App\Query\PairsQuery;
use App\Services\Pairs;


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
        $query->onlyPaid();

    	$this->template->pairs = $this->pairs->fetch($query);
//        dump( $this->template->pairs[0]->getFirstMember() ); die;
    }
    
}
