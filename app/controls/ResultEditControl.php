<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Controls;

use App\Model\Checkpoint;
use App\Model\Pair;
use Nette\Application\UI\Control;
use App\Forms\Base\Form;
use Nette\Utils\DateTime;

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
     * @var \App\Model\Result|false
     */
    protected $result;

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

        $this->result = $pair->getResultOn($checkpoint);
    }

    public function createComponentForm()
    {
        $frm = new Form();
        $frm->setAjax();
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
        $frm->onSubmit[] = function()
        {
            $this->redrawControl();
        };

        if($this->result)
        {
            $defaults = [
                'checkIn'  => $this->result->getCheckIn()  ? $this->result->getCheckIn()->format('H:i')  : null,
                'startAt'  => $this->result->getStartAt()  ? $this->result->getStartAt()->format('H:i')  : null,
                'checkOut' => $this->result->getCheckOut() ? $this->result->getCheckOut()->format('H:i') : null,
                'points'   => $this->result->getPoints()
            ];
            $frm->setDefaults($defaults);
        }

        return $frm;
    }

    public function render()
    {
        $this->template->setFile(__DIR__.'/resultEditControl.latte');
        $this->template->render();
    }


    public function formSuccess(Form $form, $values)
    {
        try {
            $date = $this->pair->getRace()->getStartTime();
            if ($values->checkIn) {
                $checkIn = DateTime::from($date)->modify($values->checkIn);
                $this->pair->checkIn($this->checkpoint, $checkIn);
            }
            if ($values->startAt) {
                $startAt = DateTime::from($date)->modify($values->startAt);
                $this->pair->startActivity($this->checkpoint, $startAt);
            }
            if ($values->checkOut) {
                $checkOut = DateTime::from($date)->modify($values->checkOut);
                $this->pair->checkOut($this->checkpoint, $checkOut, $values->points);
            }
        }
        catch(\Exception $e)
        {
            $form->addError( $e->getMessage() );
            return;
        }

        $this->result = $this->pair->getResultOn($this->checkpoint);
        $this->onSave($this, $this->result);
    }


}


interface IResultEditControlFactory
{
    /**
     * @param Pair $pair Dvojice
     * @param Checkpoint $checkpoint Stanoviště
     * @return ResultEditControl
     */
    function create(Pair $pair, Checkpoint $checkpoint);
}