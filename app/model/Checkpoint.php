<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Checkpoint
{
    use \Kdyby\Doctrine\Entities\Attributes\Identifier; // Using Identifier trait for id column

    /**
     * Závod ve kterém je tato dvojice registrovaná
     * @ORM\ManyToOne(targetEntity="Race", inversedBy="checkpoints")
     * @var Race
     */
    protected $race;

    /**
     * Číslo stanoviště (pořadí v závodu)
     * @ORM\Column(type="integer", nullable=TRUE)
     * @var integer
     */
    protected $number;

    /**
     * Název stanoviště
     * @ORM\Column(type="string", nullable=TRUE)
     * @var string
     */
    protected $name;

    /**
     * Koeficient pro násobení trestných bodů na čas
     * @ORM\Column(type="float")
     * @var float
     */
    protected $coefficient = 1;

    /**
     * Správce tohoto stanoviště
     * @ORM\ManyToOne(targetEntity="User", inversedBy="managedCheckpoints")
     * @var User
     */
    protected $manager;

    /**
     * Výsledky na tomto stanovišti
     * @ORM\OneToMany(targetEntity="Result", mappedBy="checkpoint")
     */
    protected $results;

    /**
     * Checkpoint constructor.
     * @param Race $race
     * @param int $number
     * @param string $name
     */
    public function __construct(Race $race, $number, $name)
    {
        $this->results = new ArrayCollection();
        $this->race = $race;
        $this->number = $number;
        $this->name = $name;
    }


    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @return float
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }


    /**
     * @return mixed
     */
    public function getManager()
    {
        return $this->manager;
    }


    /**
     * @return Race
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * @param Race $race
     */
    public function setRace($race)
    {
        if($race === null)
            throw new \InvalidArgumentException('name is required');

        $this->race = $race;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        if($number === null)
            throw new \InvalidArgumentException('name is required');

        $this->number = $number;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        if($name === null)
            throw new \InvalidArgumentException('name is required');
        $this->name = $name;
    }

    /**
     * @param float $coefficient
     */
    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;
    }

    /**
     * @param User $manager
     */
    public function setManager(User $manager)
    {
        $this->manager = $manager;
    }






}