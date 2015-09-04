<?php
/**
 * @project: z3v-system
 * @author: petr.sladek@skaut.cz
 */


namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Nette\Security\Identity;
use Nette\Utils\DateTime;

/**
 * @ORM\Entity
 */
class User
{
    use \Kdyby\Doctrine\Entities\Attributes\Identifier; // Using Identifier trait for id column

    /**
     * Kontantní a identifikační email
     * @ORM\Column(type="string", nullable=TRUE, unique=TRUE)
     * @var string
     */
    protected $email;

    /**
     * Přihlašovací heslo
     * @ORM\Column(type="string", nullable=TRUE)
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @var DateTime
     */
    protected $lastLogin;

    /**
     * Jméno
     * @ORM\Column(type="string", nullable=TRUE)
     * @var string
     */
    protected $name;

    /**
     * Příjmení
     * @ORM\Column(type="string", nullable=TRUE)
     * @var string
     */
    protected $surname;

    /**
     * Přezdívka
     * @ORM\Column(type="string", nullable=TRUE)
     * @var string
     */
    protected $nickname;

    /**
     * Datum narození
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @var \DateTime
     */
    protected $birthdate;

    /**
     * Adresa
     * @ORM\Embedded(class = "Address")
     * @var Address
     */
    protected $address;

    /**
     * Telefon
     * @ORM\Column(type="string", nullable=TRUE)
     * @var string
     */
    protected $phone;

    /**
     * Velikost trika
     * @ORM\Column(type="string", nullable=TRUE)
     * @var string
     */
    protected $tshirt;

    /**
     * Pokud je ST, tak stanoviště ktere kdy správcoval
     * @ORM\OneToMany(targetEntity="Checkpoint", mappedBy="manager")
     * @var ArrayCollection
     */
    protected $managedCheckpoints;

    /**
     * Prichozi notifikace
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="recipient")
     * @var ArrayCollection
     */
    protected $notifications;


    /**
     * Účasti tohoto uživatele na závodech
     * @ORM\OneToMany(targetEntity="Participation", mappedBy="user")
     * @var ArrayCollection
     */
    protected $participations;



    public function __construct()
    {
        $this->participations = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->managedCheckpoints = new ArrayCollection();
    }

    /**
     * Vrati vsechny ucasti tohoto uzivetele na jednotlivých závodech
     * @return Participation[]
     */
    public function getParticipations() {
        return $this->participations->toArray();
    }

    /**
     * Vrati ucast tohoto uzivatele v konrétním závodě
     * @return null|Partcipation
     */
    public function getParticipationInRace(Race $race) {
        $criteria  = Criteria::create()->where(Criteria::expr()->eq('race',$race));
        return $this->participations->matching($criteria)->first();
    }


    /**
     * Ma nějaké nepřečtené notifikace?
     * @return boolean
     */
    public function hasUnreadNotification() {
        $criteria  = Criteria::create()->where(Criteria::expr()->isNull('readAt'));
        return !$this->notifications->matching($criteria)->isEmpty();
    }

    /**
     * Vrati jako Security Identity object
     * @return Identity
     */
    public function toIdentity() {
        return new Identity($this->getId(), null, null);
    }

    public function getFullName()
    {
        return sprintf("%s %s", $this->name, $this->surname);
    }

    public function getFullNameWithNickname()
    {
        return $this->nickname
            ? sprintf("%s %s (%s)", $this->name, $this->surname, $this->nickname)
            : $this->getFullName();
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     * @return User
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param \DateTime $birthdate
     * @return User
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     * @return User
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getTshirt()
    {
        return $this->tshirt;
    }

    /**
     * @param string $tshirt
     * @return User
     */
    public function setTshirt($tshirt)
    {
        $this->tshirt = $tshirt;
        return $this;
    }


    /**
     * @return DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param DateTime $lastLogin
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = DateTime::from($lastLogin);
        return $this;
    }





}

class DuplicateEmailException extends \RuntimeException {

}