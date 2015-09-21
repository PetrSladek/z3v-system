<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Pair
{
    use \Kdyby\Doctrine\Entities\Attributes\Identifier; // Using Identifier trait for id column

    /**
     * @ORM\Column(type="integer", nullable=TRUE)
     * @var integer
     */
    protected $startNumber;

    /**
     * Závod ve kterém je tato dvojice registrovaná
     * @ORM\ManyToOne(targetEntity="Race", inversedBy="pairs")
     * @var Race
     */
    protected $race;

    /**
     * Členové dvojice
     * @ORM\OneToMany(targetEntity="RacerParticipation", mappedBy="pair")
     * @var ArrayCollection
     */
    protected $members;

    /**
     * Prijeli na zavod?
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    protected $arrived = false;
    
    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @var \DateTime
     */
    protected $dateStart;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @var \DateTime
     */
    protected $dateFinish;

    /**
     * ID čipu nebo čipové karty, ktere prijde v SMS
     * @ORM\Column(type="string", nullable=TRUE)
     * @var string
     */
    protected $chipId;

    /**
     * Poznámka
     * @ORM\Column(type="text", nullable=TRUE)
     * @var string
     */
    protected $internalNote;


    public function __construct(Race $race)
    {
        $this->race = $race;
        $this->members = new ArrayCollection();
    }

    public function addMember(RacerParticipation $participation) {
        $this->members->add( $participation );
    }

    /**
     * @return array|RacerParticipation[]
     */
    public function getMembers() {
        return $this->members->toArray();
    }

    /**
     * Vrati ostatni cleny ve starovní dvojici (tedy jen jednoho)
     * @param RacerParticipation $participation
     * @return \Doctrine\Common\Collections\Collection|static
     */
    protected function getOthers(RacerParticipation $participation)
    {
        $criteria = Criteria::create()->where( Criteria::expr()->neq('id', $participation->getId()) );
        return $this->members->matching($criteria);
    }


    /**
     * Vrátí druhého z dvojice
     * @param Participation $participation
     * @return RacerParticipation
     */
    public function getOtherOne(RacerParticipation $participation) {
        return $this->getOthers($participation)->first();
    }


    /**
     * Vrátí účast uživatele v této dvojici
     * @param User $user
     * @return RacerParticipation
     */
    public function getUserParticipation(User $user)
    {
        $criteria = Criteria::create()->where( Criteria::expr()->neq('user', $user) );
        $participation = $this->members->matching($criteria)->first();
        if(!$participation)
            throw new \InvalidArgumentException("User is not in this pair!");
        return $participation;
    }

    /**
     * Je uživatel členem této dvojice?
     * @param User $user
     * @return bool
     */
    public function isMember(User $user)
    {
        $criteria = Criteria::create()->where( Criteria::expr()->neq('user', $user) );
        return !$this->members->matching($criteria)->isEmpty();

    }



    /**
     * Vrátí prvního z dvojice (ten kdo ji zakládal)
     * @return RacerParticipation|null
     */
    public function getFirstMember() {
        return $this->members->get(0);
    }


    /**
     * Vrátí druhého z dvojice (ten kdo pozvání přijal)
     * @return RacerParticipation|null
     */
    public function getSecondMember() {
        return $this->members->get(1);
    }


    /**
     * Je za všechny zaplacené?
     * @return boolean
     */
    public function isPaid()
    {
        // seznam nezaplacených je prázdný
        $criteria = Criteria::create()->where( Criteria::expr()->eq('paid', false ));
        return $this->members->matching($criteria)->isEmpty();
    }

    /**
     * Je zaplaceno alespon za někoho, ale ne za všechny?
     * @return boolean
     */
    public function isPartlyPaid()
    {
        // seznam zaplacených je neprzádný, ale není zaplacená celá skupiny
        $criteria = Criteria::create()->where( Criteria::expr()->eq('paid', true ));
        $someonePaid = !$this->members->matching($criteria)->isEmpty();
        return $someonePaid && !$this->isPaid();
    }


    /**
     * Mají přidělené startovní číslo?
     * @return boolean
     */
    public function hasStartNumber()
    {
        return $this->startNumber !== null;
    }

    /**
     * @return int
     */
    public function getStartNumber()
    {
        return $this->startNumber;
    }

    /**
     * @param int $startNumber
     */
    public function setStartNumber($startNumber)
    {
        $this->startNumber = $startNumber;
    }


    /**
     * @param mixed $race
     */
    public function setRace($race)
    {
        $this->race = $race;
    }

    /**
     * @return Race
     */
    public function getRace()
    {
        return $this->race;
    }



    /**
     * @return boolean
     */
    public function isArrived()
    {
        return $this->arrived;
    }

    /**
     * @param boolean $arrived
     */
    public function setArrived($arrived)
    {
        $this->arrived = $arrived;
    }

    /**
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @param \DateTime $dateStart
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
    }

    /**
     * @return \DateTime
     */
    public function getDateFinish()
    {
        return $this->dateFinish;
    }

    /**
     * @param \DateTime $dateFinish
     */
    public function setDateFinish($dateFinish)
    {
        $this->dateFinish = $dateFinish;
    }

    /**
     * @return string
     */
    public function getChipId()
    {
        return $this->chipId;
    }

    /**
     * @param string $chipId
     */
    public function setChipId($chipId)
    {
        $this->chipId = $chipId;
    }

    /**
     * @return string
     */
    public function getInternalNote()
    {
        return $this->internalNote;
    }

    /**
     * @param string $internalNote
     */
    public function setInternalNote($internalNote)
    {
        $this->internalNote = $internalNote;
    }




}