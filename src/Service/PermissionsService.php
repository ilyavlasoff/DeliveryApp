<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class PermissionsService
{

    private $staffDbOperator;
    private $session;

    public function __construct(SessionInterface $session, StaffOperationService $staffDbOperator)
    {
        $this->staffDbOperator = $staffDbOperator;
        $this->session = $session;
    }


    public function createStaffEnvironment(UserInterface $user)
    {
        if (!in_array(User::WAREHOUSE_EMPLOYEE, $user->getRoles()))
        {
            throw new \Exception('User does have staff rights access');
        }
        try {
            $staff = $this->staffDbOperator->getStaffViaUser($user);
        }
        catch (\Exception $ex)
        {
            throw new \Exception('This employee doesn\'t exists');
        }
        $warehouse = $staff->getWarehouse();
        $this->session->set('currentStaff', $staff);
        $this->session->set('currentWarehouse', $warehouse);
    }
}