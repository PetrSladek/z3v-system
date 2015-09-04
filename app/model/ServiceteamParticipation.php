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
class ServiceteamParticipation extends Participation
{

    /**
     * Poznámka, co třeba dělá nebo tak
     * @ORM\Column(type="string", nullable=TRUE)
     * @var string
     */
    protected $note;

}