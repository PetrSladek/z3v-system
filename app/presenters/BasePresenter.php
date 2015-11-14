<?php

namespace App\Presenters;

use App\Model\Participation;
use App\Model\Race;
use App\Model\User;
use App\Services\Races;
use Latte\Template;
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

    /** @inheritdoc */
    protected function createTemplate()
    {
        $template = parent::createTemplate();
        // přidá filtr který převede počet vteřin na "3 h 05'"
        $template->addFilter('time', function ($time) {
            if($time instanceof \DateTime)
                $time = $time->getTimestamp();

            $time = (int) $time;

            return ($time < 0 ? "- " : "") . gmdate("G\h i'", abs($time));
        });
        return $template;
    }

    /**
     * Pred renderovanim sablony
     */
    protected function beforeRender()
    {
        parent::beforeRender();

        $this->template->me = $this->me;
        $this->template->race = $this->race;

        // Bude se používat Webpack devServer pro JS a CSS?
        $devServer = @$this->context->parameters['devServer'] ? : null;
        $debugMode = $this->context->parameters['debugMode'];

        $this->template->devServer = $debugMode && $devServer ? $devServer : null;
    }


}
