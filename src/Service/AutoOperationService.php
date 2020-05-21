<?php

namespace App\Service;

use App\Entity\Auto;
use App\Entity\Courier;
use App\Entity\Warehouse;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;

class AutoOperationService extends DatabaseService
{
    public function getAutoViaStateNumber(string $stateNumber)
    {
        $auto = $this->em->getRepository(Auto::class)->find($stateNumber);
        if (! $auto)
        {
            throw new \Exception("Auto $stateNumber not found");
        }
        return $auto;
    }

    public function getAutoWorkshiftHistory(Auto $auto): array
    {
        $query = $this->em->createQueryBuilder()
            ->select('e.name as name, e.surname as surname, ws.startTime as start,
             ws.endTime as end, ws.active as active')
            ->from('App\Entity\Auto', 'a')
            ->innerJoin('App\Entity\WorkShift', 'ws', Join::WITH, 'a.number = ws.autoNum')
            ->innerJoin('App\Entity\Courier', 'c', Join::WITH, 'ws.courier = c.id')
            ->innerJoin('App\Entity\Employee', 'e', Join::WITH, 'c.empId = e.id')
            ->where('a.number = :autoNum')
            ->setParameter('autoNum', $auto->getNumber())
            ->orderBy('ws.startTime')
            ->getQuery();
        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function getFisrtUsingDate(Auto $auto)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select('MONTH(MIN(w.startTime)) as month, YEAR(MIN(w.startTime)) as year')
            ->from('App\Entity\Auto', 'a')
            ->innerJoin('App\Entity\WorkShift', 'w', Join::WITH, 'a.number = w.autoNum')
            ->where('a.number = :autoNumber')
            ->setParameter('autoNumber', $auto->getNumber());
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getUsingStatistics(Auto $auto)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select('YEAR(w.startTime) as year, SUM(DATE_DIFF(w.endTime, w.startTime))')
            ->from('App\Entity\Auto', 'a')
            ->innerJoin('App\Entity\WorkShift', 'w', Join::WITH, 'a.number = w.autoNum')
            ->where('a.number = :autoNumber')
            ->setParameter('autoNumber', $auto->getNumber())
            ->groupBy('YEAR(w.startTime)');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getFreeAutoList(string $mode = 'all')
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('a')
            ->from('App\Entity\Auto', 'a')
            ->leftJoin('App\Entity\WorkShift', 'w', Join::WITH, 'w.autoNum = a.number');
        if (strtolower($mode) == 'active')
        {
            $queryBuilder->where('w.active = TRUE');
        }
        elseif (strtolower($mode) == 'inactive')
        {
            $queryBuilder->where('w.active = FALSE');
        }
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function addNewAuto(Auto $auto)
    {
        $this->em->persist($auto);
        $this->em->flush();
    }

    public function createNewAuto(string $number, string $model,
                               string $drivingCategory, float $maxCapacity, bool $isFunctional = true)
    {
        $auto = new Auto();
        $auto->setNumber($number);
        $auto->setModel($model);
        $auto->setRequiredDriveCat($drivingCategory);
        $auto->setCapacity($maxCapacity);
        $auto->setIsFunctional($isFunctional);
        $this->em->persist($auto);
        $this->em->flush();
    }

    public function getAutoForTimeAndCourier(\DateTime $starts, \DateTime $ends, Courier $courier = null)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder->select('a.number, a.model, a.requiredDriveCat as drivingCategory, a.capacity');
        $queryBuilder->distinct('a');
        $queryBuilder->from('App\Entity\Auto', 'a');
        $queryBuilder->innerJoin('App\Entity\WorkShift', 'w', Join::WITH, 'w.autoNum = a.number');

        $queryBuilder->where(
            $queryBuilder->expr()->orX(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->lt('w.startTime', ':starts'),
                    $queryBuilder->expr()->lt('w.startTime', ':ends'),
                    $queryBuilder->expr()->lt('w.endTime', ':starts'),
                    $queryBuilder->expr()->lt('w.endTime', ':ends')
                ),
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->gt('w.startTime', ':starts'),
                    $queryBuilder->expr()->gt('w.startTime', ':ends'),
                    $queryBuilder->expr()->gt('w.endTime', ':starts'),
                    $queryBuilder->expr()->gt('w.endTime', ':ends')
                )
            )
        );
        /*if (! is_null($courier))
        {
            $queryBuilder->andWhere('a.requiredDriveCat IN (:courierDriveCat)');
            $queryBuilder->setParameter('courierDriveCat', $courier->getDriveCat());
        }*/
        $queryBuilder->andWhere('a.isFunctional = TRUE');
        $queryBuilder->setParameter('starts', $starts);
        $queryBuilder->setParameter('ends', $ends);
        $query = $queryBuilder->getQuery();
        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function getAutoFields()
    {
        return ['Number', 'Model', 'Drive category', 'Capacity'];
    }
}