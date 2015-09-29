<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Listeners;

use App\Model\RacerParticipation;
use App\Services\Pairs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Kdyby\Events\EventArgs;
use Kdyby\Events\Subscriber;
use Nette\Object;

class MemberPaymentListener extends Object implements Subscriber
{
    /**
     * @var Pairs
     */
    private $pairs;


    /**
     * MemberPaymentListener constructor.
     * @param Pairs $pairs
     */
    public function __construct(Pairs $pairs)
    {
        $this->pairs = $pairs;
    }


    /**
     * Vrátí na kterou událost reafuje
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [Events::postUpdate];
    }

    /**
     * Akce na postUpdate entit RacerParticipation
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs  $args)
    {
        if (!($args->getEntity() instanceof RacerParticipation))
            return;

        /** @var RacerParticipation $member */
        $member = $args->getEntity();

        // Pokud neni zaplacenej, urcite neni zaplacena cela jeho skupina
        if(!$member->isPaid())
            return;

//        $this->pairs->tryAssignStartNumber($member->getPair());
        $pair = $member->getPair();
        if(!$pair->hasStartNumber() && $pair->isPaid())
        {
            $startNumber = $this->pairs->getNextStartNumber($pair->getRace());
            $pair->setStartNumber($startNumber);
            $args->getEntityManager()->flush($pair);
        }

    }

}