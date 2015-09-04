<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="participation", uniqueConstraints={@ORM\UniqueConstraint(name="unique_participation", columns={"race_id", "user_id"})})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"racer" = "RacerParticipation", "serviceteam" = "ServiceteamParticipation"})
 */
abstract class Participation
{
    use \Kdyby\Doctrine\Entities\Attributes\Identifier; // Using Identifier trait for id column

    /**
     * Závod kterého se uživatel zůčastňuje
     * @ORM\ManyToOne(targetEntity="Race", inversedBy="participations")
     * @ORM\JoinColumn(name="race_id", referencedColumnName="id")
     * @var Race
     */
    protected $race;


    /**
     * Ktery je to uzivatel
     * @ORM\ManyToOne(targetEntity="User", inversedBy="participations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    protected $user;



    /**
     * @return Race
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Je učast v tomto ročníku závodu jako Závodník?
     * @return bool
     */
    public function isRacer() {
        return $this instanceof RacerParticipation;
    }

    /**
     * Je učast v tomto ročníku závodu jako Servisák?
     * @return bool
     */
    public function isServiceteam() {
        return $this instanceof ServiceteamParticipation;
    }







}