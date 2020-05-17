<?php

namespace App\Controller\Ajax\Staff;

use App\Service\DeliveryOperationService;
use App\Service\ShippingOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ShipmentsAjaxController extends AbstractController
{
    private $session;
    private $shippingDBOperator;
    private $deliveryDBOperator;

    public function __construct(SessionInterface $session,
                                ShippingOperationService $shippingDBOperator,
                                DeliveryOperationService $deliveryDBOperator)
    {
        $this->session = $session;
        $this->shippingDBOperator = $shippingDBOperator;
        $this->deliveryDBOperator = $deliveryDBOperator;
    }
    public function getShipmentList(Request $request)
    {
        $warehouse = $this->session->get('currentWarehouse');
        $workshiftId = $request->request->get('id');
        $deliveries = $this->shippingDBOperator->getIncomingShipmentInformation($workshiftId, $warehouse);
        return new JsonResponse(json_encode($deliveries));
    }

    public function acceptShipment(Request $request)
    {
        $warehouse = $this->session->get('currentWarehouse');
        $storage = $request->request->get('storageValue');
        $shelf = $request->request->get('shelfValue');
        $place = $request->request->get('placeValue');
        $deliveryId = $request->request->get('deliveryId');
        $delivery = $this->deliveryDBOperator->getDeliveryById($deliveryId);
        try
        {
            if ($this->shippingDBOperator->isPlaceBusy($warehouse, $storage, $shelf, $place)['count'] === 0) {
                $this->shippingDBOperator->acceptShipmentToWarehouse($storage, $shelf, $place, $warehouse->getId(), $delivery);
            } else {
                return new JsonResponse(json_encode(['error' => 'Place is already taken']));
            }
        }
        catch (\Exception $ex) {
            return new JsonResponse(json_encode(['error' => $ex->getMessage()]));
        }
    }


    public function getShipmentGroups(Request $request)
    {
        $count = $request->request->get('count');
        $offset = $request->request->get('offset');
        $warehouse = $this->session->get('currentWarehouse');
        $shipmentGroups = $this->shippingDBOperator->getIncomingShipmentGroups($warehouse, $count, $offset);
        return new JsonResponse(json_encode($shipmentGroups));
    }
}