<?php

namespace App\Controller;

use App\Service\ShippingOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class StaffShipmentsGroupController extends  AbstractController
{
    private $shipmentDBOperator;
    private $session;

    public function __construct(ShippingOperationService $shipmentDBOperator, SessionInterface $session)
    {
        $this->shipmentDBOperator = $shipmentDBOperator;
        $this->session = $session;
    }

    public function getShipmentGroups(Request $request)
    {
        $count = $request->request->get('count');
        $offset = $request->request->get('offset');
        $warehouse = $this->session->get('currentWarehouse');
        $shipmentGroups = $this->shipmentDBOperator->getIncomingShipmentGroups($warehouse, $count, $offset);
        return new JsonResponse(json_encode($shipmentGroups));
    }

    public function displayPage()
    {
        return $this->render('pages/staff_shipments.html.twig');
    }
}