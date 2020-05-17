<?php

namespace App\Service;

use App\Entity\Auto;
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

    public function getFreeAutoList(string $mode = 'all')
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('a')
            ->from('App\Entity\Auto', 'a')
            ->innerJoin('App\Entity\WorkShift', 'w', Join::WITH, 'w.autoNum = a.number');
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

    public function addNewAuto(string $number, string $model,
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

    public function getAutoFreeForTime(\DateTime $starts, \DateTime $ends)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select('a')
            ->from('App\Entity\Auto', 'a')
            ->innerJoin('App\Entity\WorkShift', 'w', Join::WITH, 'w.autoNum = a.number')
            ->where($queryBuilder->expr()->andX(
                $queryBuilder->expr()->lt('w.startTime', ':starts'),
                $queryBuilder->expr()->lt('w.startTime', ':ends'),
                $queryBuilder->expr()->lt('w.endTime', ':starts'),
                $queryBuilder->expr()->lt('w.endTime', ':ends')
            ))
            ->orWhere($queryBuilder->expr()->andX(
                $queryBuilder->expr()->gt('w.startTime', ':starts'),
                $queryBuilder->expr()->gt('w.startTime', ':ends'),
                $queryBuilder->expr()->gt('w.endTime', ':starts'),
                $queryBuilder->expr()->gt('w.endTime', ':ends')
            ))
            ->setParameter('starts', $starts)
            ->setParameter('ends', $ends);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }


}