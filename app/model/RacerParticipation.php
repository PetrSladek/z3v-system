<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class RacerParticipation extends Participation
{

    /**
     * Ve kterém páru
     * @ORM\ManyToOne(targetEntity="Pair", inversedBy="members")
     * @ORM\JoinColumn(name="pair_id", referencedColumnName="id")
     * @var Pair
     */
    protected $pair;

    /**
     * Zaplatil?
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    protected $paid = false;

    /**
     * Prijel na zavod?
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    protected $arrived = false;

    /**
     * RacerParticipation constructor.
     * @param Pair $pair
     */
    public function __construct(Race $race, User $user, Pair $pair = null)
    {
        $this->race = $race;
        $this->pair = $pair;
        $this->user = $user;
    }

    /**
     * @return Pair
     */
    public function getPair()
    {
        return $this->pair;
    }

    /**
     * @param Pair $pair
     */
    public function setPair(Pair $pair = null)
    {
        $this->pair = $pair;
    }


    /**
     * @return boolean
     */
    public function isPaid()
    {
        return $this->paid;
    }

    /**
     * @param boolean $paid
     */
    public function setPaid($paid = true)
    {
        $this->paid = $paid;
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
    public function setArrived($arrived = true)
    {
        $this->arrived = $arrived;
    }





}