<?php
/**
 * @project: z3v-system
 * @author: petr.sladek@skaut.cz
 */

namespace App\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Race
{
    use \Kdyby\Doctrine\Entities\Attributes\Identifier; // Using Identifier trait for id column

    /**
     * Jde o aktuální ročník?
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    protected $actual = false;

    /**
     * Datum závodu (začítek víkendu)
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    protected $date;

    /**
     * Čas startu
     * @ORM\Column(type="time", nullable=TRUE)
     * @var string
     */
    protected $startTime;


    /**
     * Je možné editovat ročník?
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    protected $locked = false;

    /**
     * Do kdy je možné volit velikost trika
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @var \DateTime
     */
    protected $tshirtEndDate;


    /**
     * Umístění, kde? (v Litoměřicích)
     * @ORM\Column(type="string", nullable=TRUE)
     * @var string
     */
    protected $location;

    /**
     * Poznámka
     * @ORM\Column(type="text", nullable=TRUE)
     * @var string
     */
    protected $note;

    /**
     * Účasti na tomto závodě (Závodníci i Servisáci)
     * @ORM\OneToMany(targetEntity="Participation", mappedBy="race")
     */
    protected $participations;

    /**
     * Páry které jsou v tomto závodu
     * @ORM\OneToMany(targetEntity="Pair", mappedBy="race")
     */
    protected $pairs;

    /**
     * Stanoviště na tomto závodu
     * @ORM\OneToMany(targetEntity="Checkpoint", mappedBy="race")
     * @var Collection
     */
    protected $checkpoints;


    /**
     * Race constructor.
     * @param \DateTime $date
     */
    public function __construct(\DateTime $date)
    {
        $this->date = $date;
    }


    /**
     * @return string
     */
    public function getYear()
    {
        return $this->date->format('Y');
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }


    /**
     * @return boolean
     */
    public function isActual()
    {
        return $this->actual;
    }


    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * @return string
     */
    public function getStartTime()
    {
        return $this->startTime;
    }


    /**
     * @return boolean
     */
    public function isLocked()
    {
        return $this->locked;
    }


    /**
     * @return \DateTime
     */
    public function getTshirtEndDate()
    {
        return $this->tshirtEndDate;
    }


    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }


    /**
     * Vrátí stanoviště tohoto závodu
     * @return Checkpoint[]
     */
    public function getCheckpoints()
    {
        return $this->checkpoints->toArray();
    }


    /**
     * @param boolean $actual
     */
    public function setActual($actual)
    {
        $this->actual = $actual;
    }


    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }


    /**
     * @param string $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }


    /**
     * @param boolean $locked
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
    }


    /**
     * @param \DateTime $tshirtEndDate
     */
    public function setTshirtEndDate($tshirtEndDate)
    {
        $this->tshirtEndDate = $tshirtEndDate;
    }


    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }


    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }






}