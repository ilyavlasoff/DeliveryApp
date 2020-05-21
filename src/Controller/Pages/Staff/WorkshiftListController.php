<?php

namespace App\Controller\Pages\Staff;

use App\Controller\Service\EntitiesFieldsController;
use App\Form\WorkshiftFilterFormType;
use App\Service\CourierOperationService;
use App\Service\WorkshiftOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

class WorkshiftListController extends AbstractController
{
    private $workshiftDBOperator;
    private $entitiesDBOperator;
    private $courierDBOperator;

    public function __construct(WorkshiftOperationService $workshiftDBOperator,
                                EntitiesFieldsController $entitiesDBOperator,
                                CourierOperationService $courierDBOperator)
    {
        $this->workshiftDBOperator = $workshiftDBOperator;
        $this->entitiesDBOperator = $entitiesDBOperator;
        $this->courierDBOperator = $courierDBOperator;
    }

    public function displayPage(Request $request)
    {
        $workshiftFilterForm = $this->createForm(WorkshiftFilterFormType::class);
        $workshiftFilterForm->handleRequest($request);
        if ($workshiftFilterForm->isSubmitted() && $workshiftFilterForm->isValid())
        {
            $content = $workshiftFilterForm->getData();
            $isActive = $content['active'];
            $fromDate = new \DateTime($content['fromDate']);
            $toDate = new \DateTime($content['toDate']);
            $courierId = $content['courierID'];
            try
            {
                $courier = $this->courierDBOperator->getCourierById($courierId);
            }
            catch (\Exception $ex)
            {
                $courier = null;
            }
            $courierSurname = $content['courierSurname'];
            /*var_dump([$isActive,
                $fromDate,
                $toDate,
                $courierId,
                $courierSurname]);*/
            /**
             * TODO: Отменить установку дефолтных значений в поля даты и времени
             */
            $workshifts = $this->workshiftDBOperator->getWorkshiftList(
                $isActive,
                /*$fromDate*/null,
                /*$toDate*/null,
                $courier,
                $courierSurname
            );
        }
        else {
            $workshifts = $this->workshiftDBOperator->getWorkshiftList();
        }
        $fields = $this->entitiesDBOperator->getWorkshiftFields();
        return $this->render('pages/staff_workshift_list.html.twig', [
            'workshifts' => $workshifts,
            'fields' => $fields,
            'workshiftFilterForm' => $workshiftFilterForm->createView()
        ]);
    }
}