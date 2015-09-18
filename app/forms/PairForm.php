<?php

namespace App\Forms;

use App\Forms\Base\BaseEntityForm;
use App\Model\Address;
use App\Model\Pair;
use App\Model\User;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\BaseControl;


/**
 * Class PairForm
 * @package App\Forms
 */
class PairForm extends BaseEntityForm
{

    /** @var Pair|null */
    protected $entity;
    /**
     * @var string Class name of entity
     */
    protected $entityClass = Pair::class;


    /**
     * @return Form
     */
    protected function createComponentForm()
    {
        $frm = new Form;

        $frm->addCheckbox('arrived', 'Přijeli?');

        $cnt = $frm->addContainer('paid');
        foreach($this->entity->getMembers() as $member)
        {
            $cnt->addCheckbox($member->getId(), 'Zaplatil');
        }

        $frm->addTextArea('internal_note', 'Poznámka:');

        $frm->addSubmit('send', 'Uložit');

        $frm->onSuccess[] = [$this, 'formSuccess'];

        if($this->entity) {
            $frm->setDefaults( $this->extract() );
        }

        return $frm;
    }

    protected function extract() {

        $defaults = $this->hydrator->extract($this->entity);
        $defaults['arrived'] = $this->entity->isArrived();

        foreach($this->entity->getMembers() as $member)
        {
            $defaults['paid'][  $member->getId() ] = $member->isPaid();
        }

        return $defaults;
    }

    protected function hydrate($values) {

        // naplň datama zbytek
        $this->hydrator->hydrate((array) $values, $this->entity);

        // nastavi platby členům
        foreach($this->entity->getMembers() as $member)
        {
            $member->setPaid( $values->paid[$member->getId()] );
        }

    }


}


interface IPairFormFactory
{
    /** @return PairForm */
    function create($id);
}