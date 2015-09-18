<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\DateTime;

/**
 * @ORM\Entity
 */
class Notification
{
    const TYPE_INVITATION = 'invitation';
    const TYPE_INVITATION_ACCEPT = 'invitation_accept';
    const TYPE_INVITATION_REJECT = 'invitation_reject';
    const TYPE_PAIR_CANCEL = 'pair_cancel';
    const TYPE_MESSAGE = 'message';


    use \Kdyby\Doctrine\Entities\Attributes\Identifier; // Using Identifier trait for id column

    /**
     * Závod ke kteremu patří (aktuální ročník)
     * @ORM\ManyToOne(targetEntity="Race", inversedBy="pairs")
     */
    protected $race;

    /**
     * Kdy byla vytvořena
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Kdy byla přečtená příjemcem
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @var \DateTime
     */
    protected $readAt;

    /**
     * Od koho? null == systemová
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     * @var User
     */
    protected $sender;

    /**
     * Od koho? null == systemová
     * @ORM\ManyToOne(targetEntity="User", inversedBy="notifications")
     * @ORM\JoinColumn(name="recipient_id", referencedColumnName="id")
     * @var User
     */
    protected $recipient;


    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $type;


    /**
     * Obsah zprávy
     * @ORM\Column(type="text", nullable=TRUE)
     * @var string
     */
    protected $message;

    /**
     * Notification constructor.
     * @param $race
     * @param User|null $sender
     * @param User $recipient
     * @param string $type
     */
    public function __construct($race, User $sender = null, User $recipient, $type = self::TYPE_INVITATION)
    {
        $this->race = $race;
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->type = $type;

        $this->createdAt = new DateTime();

    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    /**
     * @return boolean
     */
    public function isRead()
    {
        return $this->readAt !== null;
    }
    /**
     * @return boolean
     */
    public function isUnread()
    {
        return $this->readAt === null;
    }

    public function markAsRead()
    {
        $this->readAt = new DateTime();
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return boolean
     */
    public function isType($type)
    {
        return $this->type === $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return User
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param User $recipient
     */
    public function setRecipient(User $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return User|null
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param User $sender
     */
    public function setSender(User $sender = null)
    {
        $this->sender = $sender;
    }

    /**
     * @return mixed
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * @param mixed $race
     */
    public function setRace($race)
    {
        $this->race = $race;
    }




}