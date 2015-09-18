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
class Address
{
    /**
     * Ulice a čp
     * @ORM\Column(type = "string", nullable=TRUE)
     */
    protected $street;

    /**
     * PSČ - poštovní směrovací číslo
     * @ORM\Column(type = "string", nullable=TRUE)
     */
    protected $postalCode;

    /**
     * Město
     * @ORM\Column(type = "string", nullable=TRUE)
     */
    protected $city;

    /**
     * Stát
     * @ORM\Column(type = "string", nullable=TRUE)
     */
    protected $country;

    /**
     * Address constructor.
     * @param $street
     * @param $postalCode
     * @param $city
     * @param $country
     */
    public function __construct($street = null, $postalCode = null, $city = null, $country = 'Česká republika')
    {
        $this->street = $street;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function __toString()
    {
        return sprintf("%s %s %s (%s)", $this->street, $this->city, $this->postalCode, $this->country);
    }


}


