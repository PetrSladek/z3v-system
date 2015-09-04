<?php
/**
 * @project: z3v-system
 * @author: petr.sladek@skaut.cz
 */

namespace App\Model;

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
     * Datum závodu
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $date;

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
    protected $tshirtEnd;

    /**
     * Čas startu
     * @ORM\Column(type="time", nullable=TRUE)
     * @var string
     */
    protected $startTime;

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
     */
    protected $checkpoints;



    public function getYear() {
        return $this->date->format('Y');
    }


    public function getLocation() {
        return $this->location;
    }

}