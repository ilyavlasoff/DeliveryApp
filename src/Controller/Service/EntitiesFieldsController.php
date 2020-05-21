<?php

namespace App\Controller\Service;

use App\Service\AutoOperationService;
use App\Service\CourierOperationService;
use App\Service\DeliveryOperationService;
use App\Service\WorkshiftOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class EntitiesFieldsController extends AbstractController
{
    private $deliveryDBOperator;
    private $workshiftDBOperator;
    private $autoDBOperator;
    private $courierDBOperator;

    public function __construct(DeliveryOperationService $deliveryDBOperator,
                                WorkshiftOperationService $workshiftDBOperator,
                                AutoOperationService $autoDBOperator,
                                CourierOperationService $courierDBOperator)
    {
        $this->deliveryDBOperator = $deliveryDBOperator;
        $this->workshiftDBOperator = $workshiftDBOperator;
        $this->autoDBOperator = $autoDBOperator;
        $this->courierDBOperator = $courierDBOperator;
    }

    public function getDeliveryFields()
    {
        try {
            $fields = $this->deliveryDBOperator->getDeliveryFields();
            $remappedFields = [];
            foreach ($fields as $field)
            {
                $readableFieldName = ucfirst(str_replace(['dep_', 'dest_', '_'], ['', '', ' '], $field));
                $remappedFields[$field] = $readableFieldName;
            }
            return new JsonResponse(json_encode(['fields' => $remappedFields]));
        }
        catch (\Exception $ex)
        {
            return new JsonResponse(json_encode(['error' => $ex->getMessage()]));
        }
    }

    public function getWorkshiftFields()
    {
        $fields = $this->workshiftDBOperator->getWorkshiftFields();
        for ($i=0; $i!=count($fields); ++$i)
        {
            $fields[$i] = ucfirst(str_replace('_', ' ', $fields[$i]));

        }
        return $fields;
    }

    public function getAutoShortFields()
    {
        $fields = $this->autoDBOperator->getAutoFields();
        return new JsonResponse(json_encode($fields));
    }

    public function getCouriersFields()
    {
        $fields = $this->courierDBOperator->getCourierFields();
        return new JsonResponse(json_encode($fields));
    }
}