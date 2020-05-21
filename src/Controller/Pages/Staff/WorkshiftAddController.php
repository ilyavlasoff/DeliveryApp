<?php

namespace App\Controller\Pages\Staff;

use App\Form\AddWorkshiftFormType;
use App\Service\AutoOperationService;
use App\Service\CourierOperationService;
use App\Service\WorkshiftOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class WorkshiftAddController extends AbstractController
{
    public $workshiftDBOperator;
    public $autoDBOperator;
    private $courierDBOperator;

    public function __construct(WorkshiftOperationService $workshiftDBOperator,
                                AutoOperationService $autoDBOperator,
                                CourierOperationService $courierDBOperator)
    {
        $this->courierDBOperator = $courierDBOperator;
        $this->autoDBOperator = $autoDBOperator;
        $this->workshiftDBOperator = $workshiftDBOperator;
    }

    public function displayPage(Request $request)
    {
        $workshiftAddForm = $this->createForm(AddWorkshiftFormType::class);
        $workshiftAddForm->handleRequest($request);
        if ($workshiftAddForm->isSubmitted() && $workshiftAddForm->isValid())
        {
            $formData = $workshiftAddForm->getData();
            try
            {
                $startDateTime = $formData['startDate'] . $formData['startTime'];
                $fromDateArr = date_parse($startDateTime);
                $fromDate = new \DateTime(date('Y-m-d H:i:s', mktime($fromDateArr['hour'], $fromDateArr['minute'],
                    $fromDateArr['second'], $fromDateArr['month'], $fromDateArr['day'], $fromDateArr['year'])));

                $endDateTime = $formData['endDate'] . $formData['endTime'];
                $toDateArr = date_parse($endDateTime);
                $toDate = new \DateTime(date('Y-m-d H:i:s', mktime($toDateArr['hour'], $toDateArr['minute'],
                    $toDateArr['second'], $toDateArr['month'], $toDateArr['day'], $toDateArr['year'])));
                if (! ($fromDate && $toDate))
                {
                    throw new \Exception('Can not convert string to date');
                }
            }
            catch (\Exception $ex)
            {
                $workshiftAddForm->addError(new FormError('Invalid date and time values'));
            }

            try
            {
                $autoNum = $formData['autoNum'];
                $auto = $this->autoDBOperator->getAutoViaStateNumber($autoNum);
            }
            catch (\Exception $ex)
            {
                $workshiftAddForm->addError(new FormError('Invalid auto number'));
            }

            try
            {
                $courierId = $formData['courierId'];
                $courier = $this->courierDBOperator->getCourierById($courierId);
            }
            catch (\Exception $ex)
            {
                $workshiftAddForm->addError(new FormError('Invalid courier identifier'));
            }
            /**
             * TODO: Обработка случая с ошибками
             */
            $addedId = $this->workshiftDBOperator->addWorkshift($courier, $auto, $fromDate, $toDate);

            return new RedirectResponse($this->generateUrl('staff_workshift_information', ['id' => $addedId]));
        }
        return $this->render('pages/staff_workshift_add.html.twig', [
            'workshiftAddForm' => $workshiftAddForm->createView()
        ]);
    }
}