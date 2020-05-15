<?php

namespace App\Service;

use App\Entity\Employee;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\Security\Core\User\UserInterface;

class StaffOperationService extends DatabaseService
{
    public function getStaffViaUser(UserInterface $user): Employee
    {
        $query = $this->em->createQueryBuilder()
            ->select('e')
            ->from('App\Entity\User', 'u')
            ->innerJoin('App\Entity\Employee', 'e', Join::WITH, 'e.userId=u.id')
            ->where('u.id = :id')
            ->setParameter('id', $user->getId())
            ->getQuery();
        return $query->getSingleResult();
    }
}