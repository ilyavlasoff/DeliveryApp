<?php

namespace App\Service;

use App\Entity\Courier;
use Doctrine\ORM\Query\Expr\Join;

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
            ->select('c')
            ->from('App\Entity\Courier', 'c');
        if (! is_null($warehouse))
        {
            $queryBuilder
                ->innerJoin('App\Entity\Employee', 'e', Join::WITH, 'c.empId = e.id')
                ->innerJoin('App\Entity\Warehouse', 'w', Join::WITH, 'e.warehouse = w.id')
                ->where('w.id = :warehouse')
                ->setParameter('warehouse', $warehouse);
        }
        $query = $queryBuilder->getQuery();
        return $query->getResult();

    }

    public function getCouriersListForSubmittedDate()
    {

    }
}