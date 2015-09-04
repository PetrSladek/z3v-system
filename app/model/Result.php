<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace app\model;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\DateTime;

/**
 * @ORM\Entity
 */
class Result
{
    use \Kdyby\Doctrine\Entities\Attributes\Identifier; // Using Identifier trait for id column


    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @var DateTime
     */
    protected $checkIn;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @var DateTime
     */
    protected $startAt;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @var DateTime
     */
    protected $checkOut;


    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $points = 0;


}