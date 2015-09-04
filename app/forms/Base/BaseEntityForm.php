<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Forms\Base;

use Doctrine\ORM\EntityNotFoundException;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Zend\Stdlib\Hydrator\HydratorInterface;

abstract class BaseEntityForm extends Nette\Application\UI\Control
{
    /** @var callable[]  function (UserForm $sender, User $entity); */
    public $onSave;

    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var null|object
     */
    protected $entity;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;


    /**
     * @var string Class name of entity
     */
    protected $entityClass;


    public function __construct(EntityManager $em, HydratorInterface $hydrator, $id = null)
    {
        parent::__construct();

        $this->em = $em;
        $this->hydrator = $hydrator;

//        $this->hydrator = new \Zend\Stdlib\Hydrator\ObjectProperty;
//        $this->hydrator = new \Zend\Stdlib\Hydrator\ArraySerializable;
//        $this->hydrator = new \Zend\Stdlib\Hydrator\ClassMethods;

        if($id) {
            $this->entity = $this->em->find($this->entityClass, $id);
            if(!$this->entity)
                throw new EntityNotFoundException;
        }
    }

    public function render() {
        $this['form']->render();
    }


    /**
     * @return Form
     */
    protected abstract function createComponentForm();

    protected abstract function hydrate($values);

    protected abstract function extract();

    public function formSuccess(Nette\Application\UI\Form $form, $values)
    {
        if(!$this->entity) {
            $this->entity = new $this->entityClass();
            $this->em->persist($this->entity);
        }

        $this->hydrate($values);

        try {
            $this->em->flush();
        } catch (\Exception $e) {
            if($e->getPrevious() && preg_match( "/Duplicate entry '(?P<value>.+)' for key '(?P<key>.+)'/i",  $e->getPrevious()->getMessage(), $match ) )
                $form['email']->addError("E-mail {$match['value']} už je jednou zaregistroavný. Nemůžete ho použít znovu.");
            else
                $form->addError($e->getMessage());
        }

        if($form->hasErrors())
            return;

        $this->onSave($this, $this->entity);
    }

}