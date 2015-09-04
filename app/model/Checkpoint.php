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
class Checkpoint
{
    use \Kdyby\Doctrine\Entities\Attributes\Identifier; // Using Identifier trait for id column

    /**
     * Závod ve kterém je tato dvojice registrovaná
     * @ORM\ManyToOne(targetEntity="Race", inversedBy="checkpoints")
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
     */
    protected $manager;
}