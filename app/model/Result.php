<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;

/**
 * @ORM\Entity
 */
class Result
{
    use \Kdyby\Doctrine\Entities\Attributes\Identifier; // Using Identifier trait for id column


    /**
     * Stanoviště, na kterém je výsledek zadán
     * @ORM\ManyToOne(targetEntity="Checkpoint", inversedBy="results")
     * @var Checkpoint
     */
    protected $checkpoint;

    /**
     * Dvojice, ke které výsledek patří
     * @ORM\ManyToOne(targetEntity="Pair", inversedBy="results")
     * @var Race
     */
    protected $pair;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @var \DateTime
     */
    protected $checkIn;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @var \DateTime
     */
    protected $startAt;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @var \DateTime
     */
    protected $checkOut;


    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $points = 0;

    /**
     * Result constructor.
     * @param Checkpoint $checkpoint
     * @param Pair $pair
     * @param \DateTime $checkIn
     * @param \DateTime $startAt
     * @param \DateTime $checkOut
     * @param int $points
     */
    public function __construct(Pair $pair, Checkpoint $checkpoint, \DateTime $checkIn, \DateTime $startAt = null, \DateTime $checkOut = null, $points = 0)
    {
        $this->pair = $pair;
        $this->checkpoint = $checkpoint;

        $this->checkIn = $checkIn;
        $this->startAt = $startAt;
        $this->checkOut = $checkOut;
        $this->points = $points;
    }


    /**
     * Čekací doba
     * @result null|int
     */
    public function getWaitingTime()
    {
        if(!$this->checkIn || !$this->startAt)
        {
            return null;
        }

        return $this->startAt->getTimestamp() - $this->checkIn->getTimestamp();
    }


    /**
     * Vrátí čas penalizace (počet vteřin)
     * @return float
     */
    public function getPenalizationTime()
    {
        return $this->getCheckpoint()->getCoefficient() * $this->getPoints() * 60;
    }


    /**
     * @return DateTime
     */
    public function getCheckIn()
    {
        return $this->checkIn;
    }

    /**
     * @param DateTime $checkIn
     */
    public function setCheckIn(DateTime $checkIn)
    {
        $this->checkIn = $checkIn;
    }

    /**
     * @return DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param DateTime $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**
     * @return DateTime
     */
    public function getCheckOut()
    {
        return $this->checkOut;
    }

    /**
     * @param DateTime $checkOut
     */
    public function setCheckOut($checkOut)
    {
        $this->checkOut = $checkOut;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param int $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }


    /**
     * @return Checkpoint
     */
    public function getCheckpoint()
    {
        return $this->checkpoint;
    }

    /**
     * @return Race
     */
    public function getPair()
    {
        return $this->pair;
    }




}