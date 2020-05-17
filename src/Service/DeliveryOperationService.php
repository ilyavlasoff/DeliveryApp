<?php

namespace App\Service;

use App\Entity\Delivery;
use App\Entity\Receiver;
use App\Entity\StatusCodes;
use App\Entity\StatusHistory;
use App\Entity\Warehouse;
use Doctrine\DBAL\Exception\DatabaseObjectNotFoundException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
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

    public function getDeliveriesListInWarehouse(Warehouse $warehouse, int $count, int $offset, array $selectFields, array $orderCriterias): array
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder->from('App\Entity\Delivery', 'd');
        $queryBuilder->innerJoin('App\Entity\Arrival', 'a', Join::WITH, 'a.delivery = d.id');
        $queryBuilder->innerJoin('App\Entity\Warehouse', 'w', Join::WITH, 'a.warehouse = w.id');
        $queryBuilder->where($queryBuilder->expr()->isNotNull('a.departureDate'));
        $queryBuilder->where('w.id = :id');
        $queryBuilder->setParameter('id', $warehouse->getId());
        $queryBuilder->setFirstResult($offset);
        $queryBuilder->setMaxResults($count);
        /*foreach ($orderCriterias as $orderCriteria => $ascDirection)
        {
            $queryBuilder
                ->addOrderBy($orderCriteria, 'ASC');
        }*/
        foreach ($selectFields as $selectField)
        {
            $queryBuilder
                ->addSelect($selectField);
        }
        $query = $queryBuilder->getQuery();
        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function getDeliveriesCountInWarehouse(Warehouse $warehouse)
    {
        $query = $this->em->createQueryBuilder()
            ->select('COUNT(d)')
            ->from ('App\Entity\Delivery', 'd')
            ->innerJoin('App\Entity\Arrival', 'a', Join::WITH, 'a.delivery = d.id')
            ->innerJoin('App\Entity\Warehouse', 'w', Join::WITH, 'a.warehouse = w.id')
            ->where('a.departureDate is NULL')
            ->where('w.id = :id')
            ->setParameter('id', $warehouse->getId())
            ->getQuery();
        return $query->getScalarResult();
    }

    public function getDeliveryStatusCodes(): array
    {
        return $this->em->getRepository(StatusCodes::class)->findAll();
    }

    public function getDeliveryStatusCodeById($id): object
    {
        return $this->em->getRepository(StatusCodes::class)->find($id);
    }

    public function getStatusCodes(): array
    {
        /*$query = $this->em->createQueryBuilder()
            ->select('sc.scode code, sc.title title')
            ->from('App\Entity\StatusCodes', 'sc')
            ->getQuery();
        return $query->execute(Query::HYDRATE_ARRAY);*/
        $rep = $this->em->getRepository(StatusCodes::class)->findAll();
        $arr = [];
        foreach ($rep as $item)
        {
            $arr[] = ['code' => $item->getScode(), 'title' => $item->getTitle()];
        }
        return $arr;
    }

    public function addStatusHistoryRecord(Delivery $delivery, StatusCodes $statusCode, \DateTimeInterface $dateTime, string $statusComment)
    {
        $statusHistoryRecord = new StatusHistory();
        $statusHistoryRecord->setStatusComment($statusComment);
        $statusHistoryRecord->setStatusSetDate($dateTime);
        $statusHistoryRecord->setDelivery($delivery);
        $statusHistoryRecord->setStatusCode($statusCode);
        $this->em->persist($statusHistoryRecord);
        $this->em->flush();
    }

    public function getDeliveryFields()
    {
        return $this->em->getClassMetadata('App\Entity\Delivery')->getColumnNames();
    }
}