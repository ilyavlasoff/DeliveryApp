<?php

namespace App\Service;

use App\Entity\Receiver;
use Doctrine\DBAL\Exception\DatabaseObjectNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientOperationService extends DatabaseService
{
    public function getClientViaUser(UserInterface $user)
    {
        $userId = $user->getId();
        return $this->em->getRepository(Receiver::class)->findOneBy(['userId' => $userId]);
    }

    public function getDeliveriesList(Receiver $client, int $quantity, int $offset = 0, bool $displayAll = true)
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
        $deliveriesCount = $this->em->createQuery('SELECT COUNT(del) cnt FROM App\Entity\Delivery del WHERE del.receiver = ?1')
            ->setParameter(1, $clientId)->getSingleResult();
        return $deliveriesCount;
    }

    public function updateClientData($id, $data)
    {
        $client = $this->em->getRepository(Receiver::class)->find($id);
        if (! $client)
        {
            throw new \Exception('Client was not found');
        }
        foreach ($data as $field => $value)
        {
            $method = 'set' . ucfirst($field);

            if (method_exists($client, $method))
            {
                call_user_func_array([$client,$method], [$value]);
            }
        }
        $this->em->flush();
    }
}