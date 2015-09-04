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


    public function __construct(Race $race)
    {
        $this->race = $race;
        $this->members = new ArrayCollection();
    }

    public function addMember(RacerParticipation $participation) {
        $this->members->add( $participation );
    }


    /**
     * Vrati ostatni cleny ve starovní dvojici (tedy jen jednoho)
     * @param RacerParticipation $participation
     * @return \Doctrine\Common\Collections\Collection|static
     */
    protected function getOthers(RacerParticipation $participation)
    {
        $criteria = Criteria::create()->where(Criteria::expr()->neq('id', $participation->getId()));
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
     * @return PairMember
     */
    public function getMember1()
    {
        return $this->member1;
    }

    /**
     * @param PairMember $member1
     */
    public function setMember1($member1)
    {
        $this->member1 = $member1;
    }

    /**
     * @return PairMember
     */
    public function getMember2()
    {
        return $this->member2;
    }

    /**
     * @param PairMember $member2
     */
    public function setMember2($member2)
    {
        $this->member2 = $member2;
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




}