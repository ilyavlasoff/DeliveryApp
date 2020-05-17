<?php

namespace App\Controller\Pages\Staff;

use App\Service\AutoOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AutoListController extends AbstractController
{
    private $autoDBOperator;

    public function __construct(AutoOperationService $autoDBOperator)
    {
        $this->autoDBOperator = $autoDBOperator;
    }

    public function displayPage()
    {
        $autos = $this->autoDBOperator->getFreeAutoList();
        return $this->render('pages/staff_auto_list.html.twig', [
            'autos' => $autos
        ]);
    }
}