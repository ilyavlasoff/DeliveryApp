<?php

namespace App\Service;

use App\Entity\Carry;
use App\Entity\Courier;
use App\Entity\Delivery;
use App\Entity\WorkShift;
use Doctrine\ORM\Query\Expr\Join;

class WorkshiftOperationService extends DatabaseService
{
    public function getWorkshiftById($id)
    {
        $workshift = $this->em->getRepository(WorkShift::class)->find($id);

        if (! $workshift)
        {
            throw new \Exception("Workshift $id not found");
        }
        return $workshift;
    }

    public function getWorkshiftWorkloading(WorkShift $workShift)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select('COALESCE(SUM(i.weight), 0) as sumWeight, COALESCE(SUM(i.weight)/a.capacity*100, 0) as loadedPercent')
            ->from('App\Entity\Workshift', 'w')
            ->innerJoin('App\Entity\Auto', 'a', Join::WITH, 'w.autoNum = a.number')
            ->leftJoin('App\Entity\Carry', 'c', Join::WITH, 'c.workshift = w.id')
            ->leftJoin('App\Entity\Delivery', 'd', Join::WITH, 'c.delivery = d.id')
            ->leftJoin('App\Entity\Item', 'i', Join::WITH, 'i.delivery = d.id')
            ->where('w.id = :workshiftId')
            ->setParameter('workshiftId', $workShift->getId())
            ->groupBy('w.id, a.number');
        $query = $queryBuilder->getQuery();
        return $query->getSingleResult();
    }

    public function getCountOfDeliveries(WorkShift $workShift)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select('COUNT(w.id) as count')
            ->from('App\Entity\WorkShift', 'w')
            ->innerJoin('App\Entity\Carry', 'c', Join::WITH, 'w.id = c.workshift');
        $query = $queryBuilder->getQuery();
        return $query->getScalarResult();
    }

    public function hasWorkshiftFreePlace(Delivery $delivery, WorkShift $workShift): bool
    {
        $queryBuilder = $this->em->createQueryBuilder();
        try
        {
            $sumWeight = $queryBuilder
                ->select('SUM(i.weight)')
                ->from('App\Entity\Delivery', 'd')
                ->join('App\Entity\Item', 'i', Join::WITH, 'i.delivery = d.id')
                ->where('d.id = :deliveryId')
                ->setParameter('deliveryId', $delivery->getId())
                ->getQuery()->getSingleResult();
        }
        catch (\Exception $ex)
        {
            /**
             * TODO: Обработать исключение
             */
        }
        $maxWeight = $workShift->getAutoNum()->getCapacity();
        $alreadyLoaded = $this->getWorkshiftWorkloading($workShift)['sumWeight'];
        return $alreadyLoaded + $sumWeight <= $maxWeight;
    }

    public function getAddReadyWorkshifts(Delivery $delivery)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select('SUM(i.weight) sumWeight, SUM(i.weight)/a.capacity*100 as loadedPercent')
            ->from('App\Entity\Workshift', 'w')
            ->innerJoin('App\Entity\Auto', 'a', Join::WITH, 'w.autoNum = a.number')
            ->innerJoin('App\Entity\Carry', 'c', Join::WITH, 'c.workshift = w.id')
            ->innerJoin('App\Entity\Delivery', 'd', Join::WITH, 'c.delivery = d.id')
            ->innerJoin('App\Entity\Item', 'i', Join::WITH, 'i.delivery = d.id')
            ->groupBy('w.id')
            ->having(
                $queryBuilder->expr()
                    ->gte('a.capacity',
                        $queryBuilder->expr()->sum('SUM(i.weight)',
                            $this->em->createQueryBuilder()
                                ->select('SUM (i2.weight)'))
                                ->from('App\Entity\Delivery', 'd2')
                                ->innerJoin('App\Entity\Item', 'i2', Join::WITH, 'i2.delivery=d2.id')
                                ->where('d2.id = :deliveryId')
                                ->setParameter('deliveryId', $delivery->getId())
                                ->getQuery()->getSingleResult()
                        )
                    );
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getWorkshiftList(bool $status = null,
                                     \DateTime $fromDate = null,
                                     \DateTime $toDate = null, Courier $courier = null, string $courierSurname = null)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select('w.id as workshiftId, w.startTime as start,
             w.endTime as end, w.active as active, a.number as number, 
             c.id as courierId, count(ca.delivery) as carryCount')
            ->from('App\Entity\WorkShift', 'w')
            ->innerJoin('App\Entity\Auto', 'a', Join::WITH, 'a.number = w.autoNum')
            ->innerJoin('App\Entity\Courier', 'c', Join::WITH, 'c.id = w.courier')
            ->innerJoin('App\Entity\Carry', 'ca', Join::WITH, 'w.id = ca.workshift');
        if (! is_null($status))
        {
            $queryBuilder
                ->andWhere('w.active = :isActive')
                ->setParameter('isActive', $status);
        }
        if(! is_null($fromDate))
        {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->gt('w.startTime', ':fromDate'))
                ->setParameter('fromDate', $fromDate);
        }
        if(! is_null($toDate))
        {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->lt('w.endTime', ':toDate'))
                ->setParameter('toDate', $toDate);
        }
        if (! is_null($courier))
        {
            $queryBuilder
                ->andWhere('c.id = :courierId')
                ->setParameter('courierId', $courier->getId());
        }
        if (! is_null($courierSurname))
        {
            $queryBuilder
                ->innerJoin('App\Entity\Employee', 'e', Join::WITH, 'c.empId = e.id')
                ->andWhere("e.surname LIKE '%:courierSurname%'")
                ->setParameter('courierSurname', $courierSurname);
        }
        $queryBuilder->groupBy('w.id, a.number, c.id');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function setWorkshiftActiveStatus($id, bool $status = true)
    {
        $workshift = $this->getWorkshiftById($id);
        $workshift->setActive($status);
    }

    public function getDeliveriesStatistics(WorkShift $workShift)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select()
            ->from('App\Entity\Workshift', 'w')
            ->innerJoin('App\Entity\Carry', 'c', Join::WITH, 'w.id = c.workshift');
            /**
             * TODO: Доделать метод
             */

    }

    public function getWorkshiftCarries($id)
    {
        $workshift = $this->getWorkshiftById($id);
        return $workshift->getCarries();
    }

    public function addWorkshift($courier, $auto, \DateTime $startTime,
                                 \DateTime $endTime, bool $defaultStatus = false)
    {
        $workshift = new WorkShift();
        $workshift->setCourier($courier);
        $workshift->setAutoNum($auto);
        $workshift->setStartTime($startTime);
        $workshift->setEndTime($endTime);
        $workshift->setActive($defaultStatus);
        $this->em->persist($workshift);
        $this->em->flush();
        return $workshift->getId();
    }

    public function changeCourier($id, $courier)
    {
        $workshift = $this->getWorkshiftById($id);
        $workshift->setCourier($courier);
        $this->em->flush();
    }

    public function changeAuto($id, $auto)
    {
        $workshift = $this->getWorkshiftById($id);
        $workshift->setAutoNum($auto);
        $this->em->flush();
    }

    public function changeStartTime($id, \DateTime $start)
    {
        $workshift = $this->getWorkshiftById($id);
        $workshift->setStartTime($start);
        $this->em->flush();
    }

    public function changeEndTime($id, \DateTime $end)
    {
        $workshift = $this->getWorkshiftById($id);
        $workshift->setEndTime($end);
        $this->em->flush();
    }

    public function addCarryItem($id, Carry $carry)
    {
        $workshift = $this->getWorkshiftById($id);
        $workshift->addCarry($carry);
        $this->em->flush();
    }

    public function deleteCarryItem($id, Carry $carry)
    {
        $workshift = $this->getWorkshiftById($id);
        $workshift->removeCarry($carry);
        $this->em->flush();
    }

    public function getWorkshiftFields()
    {
        return $this->em->getClassMetadata('App\Entity\WorkShift')->getColumnNames();
    }

}