<?php

namespace App\Repository;

use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class ChatRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Chat::class);
        $this->em = $em;
    }

    public function countUnseenMessages(User $user, Chat $chat): int
    {
        $query = $this->em->createQuery('
    SELECT COUNT(m.id) as sclr_0
    FROM App\Entity\Message m
    INNER JOIN App\Entity\Chat c WITH m.chat = c.id
    INNER JOIN m.sender s
    WHERE c.id = :chat_id
    AND m.id NOT IN (
        SELECT m2.id
        FROM App\Entity\Message m2
        INNER JOIN m2.seenBy u
        WHERE u.id = :user_id
    )
    AND s.id != :user_id
');

        $query->setParameter('chat_id', $chat->getId());
        $query->setParameter('user_id', $user->getId());
        return (int)$query->getSingleScalarResult();
    }





}
