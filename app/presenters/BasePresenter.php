<?php

namespace App\Presenters;

use App\Model\Participation;
use App\Model\Race;
use App\Model\User;
use App\Services\Races;
use Nette;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    /**
     * Entita prihlaseneho uzivatele
     * @var null|User
     */
    protected $me;

    /**
     * Entita Aktuálního ročníku závodu
     * @var Race
     */
    protected $race;

    /**
     * @var Races
     * @inject
     */
    public $races;


    protected function startup()
    {
        parent::startup();

        $this->race = $this->races->findActualRace();
        if(!$this->race)
            throw new \RuntimeException("One of race must be set as actual in database");
    }


    /**
     * Pred renderovanim sablony
     */
    protected function beforeRender()
    {
        parent::beforeRender();

        $this->template->me = $this->me;
        $this->template->race = $this->race;
    }


}
