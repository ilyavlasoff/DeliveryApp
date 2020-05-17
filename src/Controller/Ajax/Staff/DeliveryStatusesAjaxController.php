<?php

namespace App\Controller\Ajax\Staff;

use App\Service\DeliveryOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DeliveryStatusesAjaxController extends AbstractController
{
    private $deliveryDBOperator;

    public function __construct(DeliveryOperationService $deliveryDBOperator)
    {
        $this->deliveryDBOperator = $deliveryDBOperator;
    }

    public function addDeliveryStatus(Request $request)
    {
        try {
            $statusCode = $request->request->get('statusCode');
            $deliveryId = $request->request->get('deliveryId');
            $message = $request->request->get('message');

            $code = $this->deliveryDBOperator->getDeliveryStatusCodeById($statusCode);
            $delivery = $this->deliveryDBOperator->getDeliveryById($deliveryId);
            $deliveredTime = new \DateTime();
            $this->deliveryDBOperator->addStatusHistoryRecord($delivery, $code, $deliveredTime, $message);
        }
        catch (\Exception $ex) {
            return new JsonResponse(json_encode(['error' => $ex->getMessage()]));
        }

        return new JsonResponse(json_encode(['ok' => 'ok']));
    }

    public function getStatusCodes()
    {
        try {
            $statusCodes = $this->deliveryDBOperator->getStatusCodes();
        }
        catch (\Exception $ex)
        {
            $statusCodes = ['error' => $ex->getMessage()];
        }
        return new JsonResponse(json_encode($statusCodes));
    }
}