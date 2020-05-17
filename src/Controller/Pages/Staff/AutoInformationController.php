<?php

namespace App\Controller\Pages\Staff;

use App\Service\AutoOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AutoInformationController extends AbstractController
{
    private $autoDBOperator;

    public function __construct(AutoOperationService $autoDBOperator)
    {
        $this->autoDBOperator = $autoDBOperator;
    }

    public function displayPage($id)
    {
        try
        {
            $auto = $this->autoDBOperator->getAutoViaStateNumber($id);
            $workshiftHistory = $this->autoDBOperator->getAutoWorkshiftHistory($auto);
        }
        catch (\Exception $ex)
        {
            /**
             * TODO: Обработать исключение
             */
        }
        return $this->render('pages/staff_auto_information.html.twig', [
            'auto' => $auto,
            'workshiftHistory' => $workshiftHistory
        ]);
    }
}