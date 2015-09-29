<?php
/**
 * @project: z3v-system
 * @author: Petr Sládek <petr.sladek@skaut.cz>
 */

namespace App\Services;

use App\Model\Notification;
use App\Model\Pair;
use App\Model\Race;
use App\Model\RacerParticipation;
use App\Model\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\QueryException;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Persistence\Query;
use Nette\Object;

class Pairs extends Object
{
    private $em;
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Pair::class);
    }

    /**
     * Vrátí následující startovní číslo v zadaném závodě
     * @param Race $race
     * @return int
     */
    public function getNextStartNumber(Race $race)
    {
        $query = $this->em->createQuery("SELECT MAX(p.startNumber) FROM app:Pair p WHERE p.race = :race");
        $query->setParameter(":race", $race);

        return (int) $query->getSingleScalarResult() + 1;
    }

//    public function tryAssignStartNumber(Pair $pair)
//    {
//        $query = $this->em->createQuery("
//            UPDATE app:Pair pa
//            SET pa.startNumber = (SELECT MAX(pp.startNumber) FROM app:Pair pp WHERE pp.race = pa.race)
//            WHERE pa = :pair
//              AND pa.startNumber IS NULL
//              AND NOT EXISTS (SELECT rp.paid FROM app:RacerParticipation rp WHERE rp.paid = 0 AND rp.pair = pa)
//        ");
//        $query->setParameter('pair',$pair);
//        $query->execute();
//    }

    /**
     * Vytvoří pár pro daný závod
     * @param Race $race
     * @param User $user1
     * @param User $user2
     * @throws \RuntimeException
     */
    public function createPair(Race $race, User $user1, User $user2)
    {
        try {
            $this->em->transactional(function () use (&$pair, $race, $user1, $user2) {
                // vytvořím Pár
                $pair = new Pair($race);
                $this->em->persist($pair);

                // Přidám mu prvního zavodnika
                $participation = new RacerParticipation($race, $user1, $pair);
                $this->em->persist($participation);

                // Přidám mu druhyho zavodnika
                $participation = new RacerParticipation($race, $user2, $pair);
                $this->em->persist($participation);

                // Případné duplikace by měla vyřešit DB
                $this->em->flush();
            });
        }
        catch (UniqueConstraintViolationException $e)
        {
            throw new \RuntimeException('Tuto dvojici nelze vytvořit protože některý ze závodníků se již závodu účastní.');
        }

        return $pair;
    }


    /**
     * Zruší účast dvojice na závodě
     * @param Pair $pair
     */
    public function cancelPair(Pair $pair, User $by = null)
    {

        // pošlu notifikaci oboum závodníkům
        // (pokud je to zrušeno systémem, pokud někým, tak jen tomu druhému
        if($by === null)
        {
            $recipients = [
                $pair->getFirstMember()->getUser(),
                $pair->getSecondMember()->getUser(),
            ];
            foreach ($recipients as $recipient)
            {
                $recipientParticipation = $pair->getUserParticipation($recipient);

                $notification = new Notification($pair->getRace(), $by, $recipient, Notification::TYPE_PAIR_CANCEL);
                $notification->setMessage( $pair->getOtherOne($recipientParticipation)->getUser()->getFullNameWithNickname() );
                $this->em->persist($notification);
            }

        }
        else
        {
            if(!$pair->isMember($by))
                throw new \RuntimeException("User is not member of this pair.");

            $byParticipation = $pair->getUserParticipation($by);
            $recipient = $pair->getOtherOne($byParticipation)->getUser();

            $notification = new Notification($pair->getRace(), $by, $recipient, Notification::TYPE_PAIR_CANCEL);
            $this->em->persist($notification);
        }

        // smažu dvojici
        $this->em->remove($pair);
        // RacerParticipation-s removes cascade

        $this->em->flush();
    }


    /**
     * Překlopí stav platby jednomu závodníkovi
     * @param RacerParticipation $membern
     */
    public function toggleMemberPayment(RacerParticipation $member)
    {
        $member->setPaid( !$member->isPaid() );
        $this->em->flush();
    }

    /**
     * Překlopí stav příjezdu dvojice
     * @param Pair $pair
     */
    public function toggleArrived(Pair $pair)
    {
        $pair->setArrived( !$pair->isArrived() );
        $this->em->flush();
    }


    /**
     * @param \Kdyby\Persistence\Query|\Kdyby\Doctrine\QueryObject $queryObject
     * @param int $hydrationMode
     * @throws QueryException
     * @return array|\Kdyby\Doctrine\ResultSet
     */
    public function fetch(Query $queryObject, $hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        return $this->repository->fetch($queryObject, $hydrationMode);
    }
}