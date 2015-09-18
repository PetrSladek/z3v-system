<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Forms\Controls;


use Nette\Forms\Controls\SelectBox;

class Select2 extends SelectBox
{
    public function __construct($label = null, $ajaxUrl = null)
    {
        parent::__construct($label, null);
        $this->setAjaxUrl($ajaxUrl);
        $this->setAttribute('class', 'select2');
    }


    public function getValue()
    {
        return $this->value;
    }


    public function setAjaxUrl($ajaxUrl)
    {
        $this->setAttribute('data-select2-url', $ajaxUrl);
    }

}