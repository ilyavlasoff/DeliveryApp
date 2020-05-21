<?php

namespace App\Controller\Pages\Staff;

use App\Form\AddWorkshiftFormType;
use App\Service\AutoOperationService;
use App\Service\CourierOperationService;
use App\Service\WorkshiftOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WorkshiftInformationController extends AbstractController
{
    private $workshiftDBOperator;

    public function __construct(WorkshiftOperationService $workshiftDBOperator)
    {
        $this->workshiftDBOperator = $workshiftDBOperator;
    }

    public function displayPage(string $id)
    {
        try
        {
            $workshift = $this->workshiftDBOperator->getWorkshiftById($id);
        }
        catch (\Exception $ex)
        {
            /**
             * TODO: Обработать исключение
             */
        }
        $loading = $this->workshiftDBOperator->getWorkshiftWorkloading($workshift);
        $carries = $this->workshiftDBOperator->getWorkshiftCarries($workshift);
        $auto = $workshift->getAutoNum();
        $courier = $workshift->getCourier();
        $deliveriesCount = $this->workshiftDBOperator->getCountOfDeliveries($workshift);


        return $this->render('pages/staff_workshift_view.html.twig', [
            'workshift' => $workshift,
            'loading' => $loading,
            'carries' => $carries,
            'auto' => $auto,
            'courier' => $courier,
            'count' => $deliveriesCount
        ]);
    }
}