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

    /**
     * Result constructor.
     * @param DateTime $checkIn
     * @param DateTime $startAt
     * @param DateTime $checkOut
     * @param int $points
     */
    public function __construct(DateTime $checkIn, DateTime $startAt = null, DateTime $checkOut = null, $points = 0)
    {
        $this->checkIn = $checkIn;
        $this->startAt = $startAt;
        $this->checkOut = $checkOut;
        $this->points = $points;
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
    public function setCheckIn($checkIn)
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




}