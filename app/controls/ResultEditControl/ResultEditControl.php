<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Controls\ResultEditControl;

use App\Model\Checkpoint;
use App\Model\Pair;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class ResultEditControl extends Control
{

    /** @var callable[]  function (ResultEditControl $sender, Result $result); */
    public $onSave;

    /**
     * @var Pair
     */
    protected $pair;
    /**
     * @var Checkpoint
     */
    protected $checkpoint;

    /**
     * ResultEditControl constructor.
     * @param Pair $pair
     * @param Checkpoint $checkpoint
     */
    public function __construct(Pair $pair, Checkpoint $checkpoint)
    {
        parent::__construct();

        $this->pair = $pair;
        $this->checkpoint = $checkpoint;
    }

    public function createComponentForm()
    {
        $frm = new Form();
        $frm->addText('checkIn', 'Příchod')
            ->setAttribute('placeholder', '00:00');
        $frm->addText('startAt', 'Start')
            ->setAttribute('placeholder', '00:00');
        $frm->addText('checkOut', 'Odchod')
            ->setAttribute('placeholder', '00:00');
        $frm->addText('points', 'Získané body')
            ->setDefaultValue(0)
            ->setAttribute('type','number')
            ->addRule($frm::INTEGER);
        $frm->addSubmit('send', 'Uložit');

        $frm->onSuccess[] = callback($this, 'formSuccess');

        return $frm;
    }

    public function render()
    {
        $this['form']->render();
    }


    public function formSuccess($form, $values)
    {
        $this->pair->checkIn($this->checkpoint, $values->checkIn);
        $this->pair->start($this->checkpoint, $values->startAt);
        $this->pair->checkOut($this->checkpoint, $values->checkOut, $values->points);

        $result = $this->pair->getResultFrom($this->checkpoint);

        $this->onSave($this, $result);
    }


}