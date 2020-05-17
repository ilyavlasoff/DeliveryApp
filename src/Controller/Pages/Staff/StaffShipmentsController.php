<?php

namespace App\Controller\Pages\Staff;

use App\Entity\Arrival;
use App\Entity\Warehouse;
use App\Service\DeliveryOperationService;
use App\Service\ShippingOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class StaffShipmentsController extends AbstractController
{




    public function displayPage(string $id)
    {
        return $this->render('pages/staff_shipment_details.html.twig', [
            'id' => $id
        ]);
    }
}