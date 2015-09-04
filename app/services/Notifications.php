<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Services;

use App\Model\Notification;
use App\Model\Pair;
use App\Model\PairMember;
use App\Model\Race;
use App\Model\RacerParticipation;
use App\Model\ServiceteamParticipation;
use App\Model\User;
use Doctrine\ORM\EntityNotFoundException;
use Kdyby\Doctrine\EntityManager;
use Nette\Object;

class Notifications extends Object
{
    private $em;
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Notification::class);
        // $this->articles = $em->getRepository(App\Article::getClassName()); // for older PHP
    }


    /**
     * @param $id
     * @return null|Notification
     */
    public function findById($id) {
        return $this->em->find(Notification::class, $id);
    }

    /**
     * @param $id
     * @return Notification
     * @throw EntityNotFoundException
     */
    public function getById($id) {
        $notification = $this->findById($id);
        if(!$notification)
            throw new EntityNotFoundException;

        return $notification;
    }

    public function rejectInvitation(User $recipient, Notification $notification) {

        if(!$notification->isType(Notification::TYPE_INVITATION))
            throw new \InvalidArgumentException("Wrong notification type");
        if($notification->getRecipient() != $recipient)
            throw new \InvalidArgumentException("Wrong recipient");

        // oznacim notifikaci jako přečtenou
        $notification->markAsRead();

        // pošlu notifikaci odesílateli o tom, že byla pozvánka odmítnuta
        $reject = new Notification($notification->getRace(), $notification->getRecipient(), $notification->getSender(), Notification::TYPE_INVITATION_REJECT);
        $this->em->persist($reject);

        // splachnu to do DB
        $this->em->flush();
    }

    public function acceptInvitation(User $recipient, Notification $notification) {

        if(!$notification->isType(Notification::TYPE_INVITATION))
            throw new \InvalidArgumentException("Wrong notification type");
        if($notification->getRecipient() != $recipient)
            throw new \InvalidArgumentException("Wrong recipient");

        // Oznacim notifikaci jako přečtenou
        $notification->markAsRead();
        $this->em->flush();

        $race = $notification->getRace();
        $sender = $notification->getSender();
        $recipient = $notification->getRecipient();

        // Ověřím jestli se kolega může přihlásit do závodu
        if($p = $sender->getParticipationInRace($race)) {
            if($p instanceof ServiceteamParticipation)
                throw new \RuntimeException($sender->getFullNameWithNickname() . " už se bohužel do závodu přihásil jako Servisák");
            if($p instanceof RacerParticipation)
                throw new \RuntimeException($sender->getFullNameWithNickname() . " už se bohužel do závodu přihásil ve dvojici s někým jiným");
        }

        // Ověřím jestli já se můžu přihlásit do závodu
        if($p = $recipient->getParticipationInRace($race)) {
            if($p instanceof ServiceteamParticipation)
                throw new \RuntimeException("Už jsi se do závodu přihásil jako Servisák, nemůžete být zárověň závodník");
            if($p instanceof RacerParticipation)
                throw new \RuntimeException("Už jsi ve dvojici s někým jiným. Nejprve musíš stávající dvojici zrušit");
        }

        // vytvořím Pár
        $pair = new Pair($notification->getRace());
        $this->em->persist($pair)->flush();

        // Přidám mu prvního zavodnika
        $participation = new RacerParticipation($race, $sender, $pair);
        $this->em->persist($participation);

        // jen pro sychr
        $pair->addMember($participation);
        $sender->addParticipation($participation);

        // Přidám mu druhyho zavodnika
        $participation = new RacerParticipation($race, $recipient, $pair);
        $this->em->persist($participation);

        // jen pro sychr
        $pair->addMember($participation);
        $recipient->addParticipation($participation);

        // pošlu notifikaci odesílateli o tom, že byla pozvánka přijata
        $reject = new Notification($race, $recipient, $sender, Notification::TYPE_INVITATION_ACCEPT);
        $this->em->persist($reject);

        // splachnu to do DB
        $this->em->flush();
    }


    public function findUserLastNotifications(User $user, $limit = 10)
    {
        return $this->repository->findBy(['recipient'=>$user], ['createdAt'=>'desc'], $limit);
    }

    public function findUserUnreadNotifications(User $user, $limit = null, $offset = null)
    {
        return $this->repository->findBy(['recipient'=>$user, 'readAt' => null], ['createdAt'=>'desc'], $limit, $offset);
    }
}