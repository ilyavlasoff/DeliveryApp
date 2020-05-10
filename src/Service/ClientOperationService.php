<?php

namespace App\Service;

use App\Entity\Receiver;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientOperationService extends DatabaseService
{
    public function getClientViaUser(UserInterface $user)
    {
        $userId = $user->getId();
        return $this->em->getRepository(Receiver::class)->findOneBy(['userId' => $userId]);
    }

    public function getDeliveriesList(Receiver $client, int $quantity, int $offset = 0)
    {
        $clientId = $client->getId();
        return $this->em->createQuery(
            'SELECT del FROM App\Entity\Delivery del 
            WHERE del.receiver = ?1')
            ->setParameter(1, $clientId)
            ->setMaxResults($quantity)
            ->setFirstResult($offset)
            ->getResult();
    }

    public function getDeliveriesStatistics(Receiver $client)
    {
        $clientId = $client->getId();
        return $this->em->createQuery(
            'SELECT COUNT(del) cnt 
            FROM App\Entity\Delivery del 
            WHERE del.receiver = ?1')
            ->setParameter(1, $clientId)
            ->getSingleResult();
    }
}