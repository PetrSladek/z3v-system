<?php

namespace App\Forms;


use App\Model\Race;
use App\Model\User;
use App\Services\Pairs;
use Doctrine\ORM\Query;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Control;
use App\Forms\Base\Form;


/**
 * Class PairAddForm
 * @package App\Forms
 */
class PairAddForm extends Control
{


    /** @var callable[]  function (PairAddForm $sender, Pair $entity); */
    public $onSave;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Pairs
     */
    protected $pairs;

    /**
     * @var Race Aktuální závod
     */
    protected $race;


    public function __construct(EntityManager $em, Pairs $pairs, Race $race)
    {
        parent::__construct();

        $this->em = $em;
        $this->pairs = $pairs;
        $this->race = $race;
    }

    public function render()
    {
        $this['form']->render();
    }

    /**
     * @return Form
     */
    protected function createComponentForm()
    {
        $frm = new Form;

        $cnt = $frm->addContainer('members');
        $cnt->addSelect2(0, '1. účastník', $this->link('members!'))
            ->setRequired();
        $cnt->addSelect2(1, '2. účastník', $this->link('members!'))
            ->setRequired();

        $frm->addSubmit('send', 'Uložit dvojici');

        $frm->onSuccess[] = [$this, 'formSuccess'];
        $frm->onSubmit;

        return $frm;
    }

    public function formSuccess(Form $form, $values)
    {

        /** @var User $user1 */
        $user1 = $this->em->find(User::class, $values->members[0]);
        /** @var User $user2 */
        $user2 = $this->em->find(User::class, $values->members[1]);

        // vytvořím dvojici
        try
        {
            $pair = $this->pairs->createPair($this->race, $user1, $user2);
        }
        catch (\RuntimeException $e)
        {
            $form->addError($e->getMessage());
            return;
        }

        $this->onSave($this, $pair);
    }


    public function handleMembers()
    {
        $q = $this->presenter->getParameter('q');

        $qb = $this->em->createQueryBuilder();
        $qb->select("PARTIAL u.{id,name,surname,nickname}")
            ->from(User::class, 'u')
            ->where('CONCAT(u.name, \' \', u.surname, \' \', u.nickname) LIKE :query');

        /** @var User[] $users */
        $users = $qb->getQuery()
            ->setParameter('query',"%$q%")
            ->getResult();
        $data = [];

        foreach($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'text' => $user->getFullNameWithNickname()
            ];
        }

        $this->presenter->sendJson($data);
    }

}


interface IPairAddFormFactory
{
    /** @return PairAddForm */
    function create(Race $race);
}