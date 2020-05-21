<?php

namespace App\Service;

use App\Entity\Auto;
use App\Entity\Courier;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use PhpParser\Node\Expr\Cast\Object_;

class CourierOperationService extends DatabaseService
{
    public function getCourierById($id)
    {
        $courier = $this->em->getRepository(Courier::class)->find($id);
        if(! $courier)
        {
            throw new \Exception("Courier $id not found");
        }
        return $courier;
    }

    public function addCourier($employeeId, array $drivingCategory)
    {
        $courier = new Courier();
        $courier->setEmpId($employeeId);
        $courier->setDriveCat($drivingCategory);
        $this->em->persist($courier);
        $this->em->flush();
    }

    public function getCouriersList($warehouse = null)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select('c.id as courierId, c.driveCat as drivingCategory, 
            e.name as name, e.surname as surname, w.id as warehouse')
            ->from('App\Entity\Courier', 'c')
            ->innerJoin('App\Entity\Employee', 'e', Join::WITH, 'c.empId = e.id')
            ->innerJoin('App\Entity\Warehouse', 'w', Join::WITH, 'e.warehouse = w.id');
        if (! is_null($warehouse))
        {
            $queryBuilder
                ->where('w.id = :warehouse')
                ->setParameter('warehouse', $warehouse);
        }
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getAvailableCouriersList(\DateTime $from, \DateTime $to,  $auto = null)
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder->select('c.id as id, e.name as name, e.surname as surname, c.driveCat as driveCat')
            ->from('App\Entity\Courier', 'c')
            ->innerJoin('App\Entity\WorkShift', 'w', Join::WITH, 'w.courier = c.id')
            ->innerJoin('App\Entity\Employee', 'e', Join::WITH, 'c.empId = e.id')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->gt('w.startTime', ':starts'),
                        $queryBuilder->expr()->gt('w.endTime', ':starts'),
                        $queryBuilder->expr()->gt('w.startTime', ':ends'),
                        $queryBuilder->expr()->gt('w.endTime', ':ends')
                    ),
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->lt('w.startTime', ':starts'),
                        $queryBuilder->expr()->lt('w.endTime', ':starts'),
                        $queryBuilder->expr()->lt('w.startTime', ':ends'),
                        $queryBuilder->expr()->lt('w.endTime', ':ends')
                    )
                )
            );
        /*if (! is_null($auto))
        {
            $queryBuilder->innerJoin('App\Entity\Auto', 'a', Join::WITH, 'a.number = w.autoNum')
                ->andWhere(':autoRequire IN (c.driveCat)')
                ->setParameter('autoRequire', $auto->getRequiredDriveCat());
        }*/
        $queryBuilder
            ->setParameter('starts', $from)
            ->setParameter('ends', $to);
        $query = $queryBuilder->getQuery();
        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function getCourierFields()
    {
        return ['ID', 'Name', 'Surname', 'Drive category'];
    }

    public function getExtendedCourierInformation($id)
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select("e.id, e.name, e.surname, e.patronymic, e.passport, u.email, 
            ap.appointmentName as appointment, c.driveCat as drivingCategory, w.id, 
            CONCAT(w.country, ' ', w.region, ' ', w.city, ' ', w.street, ' ', w.building) as warehouseAddress")
            ->from('App\Entity\Courier', 'c')
            ->innerJoin('App\Entity\Employee', 'e', Join::WITH, 'c.empId = e.id')
            ->innerJoin('App\Entity\Appointment', 'ap', Join::WITH, 'e.appointment = ap.id')
            ->innerJoin('App\Entity\User', 'u', Join::WITH, 'e.userId = u.id')
            ->innerJoin('App\Entity\Warehouse', 'w', Join::WITH, 'e.warehouse = w.id')
            ->where('w.id = :workshiftId')
            ->setParameter('workshiftId', $id);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

}