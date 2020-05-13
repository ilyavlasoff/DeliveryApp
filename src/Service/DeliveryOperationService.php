<?php

namespace App\Service;

use App\Entity\Delivery;
use App\Entity\Receiver;
use Doctrine\DBAL\Exception\DatabaseObjectNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class DeliveryOperationService extends DatabaseService
{
    public function getDeliveryById($id): object
    {
        $delivery = $this->em->getRepository(Delivery::class)->find($id);
        if (!$delivery)
        {
            throw new \Exception('Item not found');
        }
        return $delivery;
    }

    public function getLastStatus($id, $count = 1): array
    {
        $lastStatus = $this->em->createQueryBuilder()
            ->select('sh')
            ->from('App\Entity\StatusHistory', 'sh')
            ->join('sh.delivery', 'd')
            ->where('d.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults($count)
            ->setFirstResult(0)
            ->orderBy('sh.statusSetDate', 'DESC')
            ->getQuery()
            ->getResult();
        return $lastStatus;
    }

    public function getDeliveryContents($id): array
    {
        $deliveryContents = $this->em->createQueryBuilder()
            ->select('i.name, i.weight, c.name category, c.fireDanger, c.toxic, c.explosive')
            ->from('App\Entity\Item', 'i')
            ->join('i.delivery', 'd')
            ->join('i.category', 'c')
            ->where('d.id = :id')
            ->setParameter('id', $id)
            ->orderBy('i.name')
            ->getQuery()
            ->getResult();
        return $deliveryContents;
    }

    public function getPaymentInformationAboutDelivery($id)
    {
        /**
         * TODO: 'Добавить дату платежа'
         */
        $paymentInfo = $this->em->createQueryBuilder()
            ->select('p.status, t.name tariff, t.price, d.routeLength len, p.sum, p.uip')
            ->from('App\Entity\Payments', 'p')
            ->join('p.delivery', 'd')
            ->join('d.type', 't')
            ->where('d.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
        return $paymentInfo;
    }
}