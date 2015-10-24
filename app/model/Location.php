<?php
/**
 * @project: z3v-system
 * @author: petr.sladek@skaut.cz
 */


namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Embeddable
 */
class Location
{
    /**
     * Latitude
     * @ORM\Column(type = "float")
     */
    protected $lat;

    /**
     * Longtitude
     * @ORM\Column(type = "float")
     */
    protected $lng;


    /**
     * Location constructor.
     * @param $lat
     * @param $lng
     */
    public function __construct($lat, $lng)
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }


    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }


    /**
     * @return mixed
     */
    public function getLng()
    {
        return $this->lng;
    }


    public function __toString()
    {
        return sprintf("%s %s", $this->lat, $this->lng);
    }


}


