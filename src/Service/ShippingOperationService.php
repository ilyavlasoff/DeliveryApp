<?php

namespace App\Service;

use App\Entity\Arrival;
use App\Entity\Carry;
use App\Entity\Delivery;
use App\Entity\Warehouse;
use App\Entity\WorkShift;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;

class ShippingOperationService extends DatabaseService
{
    public function getShipmentById($id)
    {
        $shipment = $this->em->getRepository(Carry::class)->find($id);
        return $shipment;
    }

    public function getIncomingShipmentGroups(Warehouse $toWarehouse, int $count, int $offset)
    {
        $transportingWorkshifts = $this->em->createQueryBuilder()
            ->select('w.id wid, w.startTime, w.endTime, w.active, count(d) as count, a.number, e.name, e.surname, e.patronymic')
            ->from('App\Entity\Carry', 'c')
            ->innerJoin('App\Entity\WorkShift', 'w', Join::WITH, 'c.workshift = w.id')
            ->innerJoin('App\Entity\Courier', 'cour', Join::WITH, 'w.courier = cour.id')
            ->innerJoin('App\Entity\Employee', 'e', Join::WITH, 'e.id = cour.empId')
            ->innerJoin('App\Entity\Auto', 'a', Join::WITH, 'w.autoNum = a.number')
            ->innerJoin('App\Entity\Delivery', 'd', Join::WITH, 'c.delivery = d.id')
            ->where('c.toWarehouse = :warehouse')
            ->setParameter('warehouse', $toWarehouse)
            ->groupBy('w.id, a.number, e.id')
            ->setFirstResult($offset)
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
        return $transportingWorkshifts;
    }

    public function getIncomingShipmentInformation(int $workShift, Warehouse $warehouse)
    {
        $deliveries = $this->em->createQueryBuilder()
            ->select("COUNT(i) as itemsCount, SUM(i.weight) as itemsWeight,
            CONCAT(d.destCountry,', ', d.destCity, ', ', d.destStreet, ', ', d.destHouse, ', ', d.destFlat) as address,
            d.destPostcode as postcode, d.id as id")
            ->from('App\Entity\Carry', 'c')
            ->innerJoin('App\Entity\WorkShift', 'w', Join::WITH, 'c.workshift = w.id')
            ->innerJoin('App\Entity\Delivery', 'd', Join::WITH, 'c.delivery = d.id')
            ->innerJoin('App\Entity\Item', 'i', Join::WITH, 'i.delivery = d.id')
            ->where('c.toWarehouse = :warehouse')
            ->andWhere('w.id = :workshift')
            ->setParameter('warehouse', $warehouse)
            ->setParameter('workshift', $workShift)
            ->groupBy('d')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        return $deliveries;
    }

    public function acceptShipmentToWarehouse(int $storage, int $shelf, int $place, $warehouseId, $delivery)
    {
        $warehouse = $this->em->getRepository(Warehouse::class)->find($warehouseId);
        $arrival = new Arrival();
        $arrival->setArrivalDate(new \DateTime());
        $arrival->setStorage($storage);
        $arrival->setShelf($shelf);
        $arrival->setPlace($place);
        $arrival->setWarehouse($warehouse);
        $arrival->setDelivery($delivery);
        $this->em->persist($arrival);
        $this->em->flush();
    }

    public function isPlaceBusy($warehouse, int $storage, int $shelf, int $place)
    {
        return $this->em->createQueryBuilder()
            ->select('COUNT(a) as count')
            ->from('App\Entity\Arrival', 'a')
            ->where('a.warehouse = :warehouse')
            ->setParameter('warehouse', $warehouse)
            ->andWhere('a.storage = :storage')
            ->setParameter('storage', $storage)
            ->andWhere('a.shelf = :shelf')
            ->setParameter('shelf', $shelf)
            ->andWhere('a.place = :place')
            ->setParameter('place', $place)
            ->getQuery()
            ->getSingleResult();
    }
}