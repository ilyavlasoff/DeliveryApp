<?php

namespace App\Controller\Pages\Staff;

use App\Entity\StatusHistory;
use App\Entity\Warehouse;
use App\Form\AddDeliveryStatusFormType;
use App\Service\DeliveryOperationService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class StaffDeliveriesController extends AbstractController
{

    public function displayPage(Request $request)
    {
        return $this->render('pages/staff_deliveries.html.twig');
    }
}