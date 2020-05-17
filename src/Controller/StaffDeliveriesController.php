<?php

namespace App\Controller;

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
    private $deliveryDBOperator;

    public function __construct(DeliveryOperationService $deliveryDBOperator)
    {
        $this->deliveryDBOperator = $deliveryDBOperator;
    }

    public function getDeliveriesList(Request $request, SessionInterface $session)
    {
        $offset = $request->request->get('offset');
        $count = $request->request->get('count');
        $fields = (array)$request->request->get('fields');
        $sort = (array)$request->request->get('sort');

        $warehouse = $session->get('currentWarehouse');
        //$converted = settype($warehouse, Warehouse::class);

        if (!is_numeric($offset) || !is_numeric($count))
        {
            /**
             * TODO "Добавить исключение когда аргументы не числовые"
             */
        }
        try {
            $entityFields = [];
            foreach ($fields as $field)
            {
                $understrokePosition = strpos($field, '_');
                if ($understrokePosition !== false) {
                    $newStr = substr($field, 0, $understrokePosition) .
                        strtoupper(substr($field, $understrokePosition + 1, 1)) .
                        substr($field, $understrokePosition + 2);
                }
                else {
                    $newStr = $field;
                }
                $entityFields[] = 'd.' . $newStr;
            }
            $content = $this->deliveryDBOperator->getDeliveriesListInWarehouse($warehouse, $count, $offset, $entityFields, $sort);
        }
        catch (\Exception $ex)
        {
            return new JsonResponse(['error' => $ex->getMessage()]);
        }
        $loadedCount = count($content);
        $totalCount = $this->deliveryDBOperator->getDeliveriesCountInWarehouse($warehouse)[0];
        $fields = [];
        if (!empty($content))
        {
            foreach ($content[0] as $index => $item)
            {
                $fields[] = $index;
            }
        }
        return new JsonResponse(json_encode(["fields" => $fields, "content" => $content, "loadedCount" => $loadedCount, "totalCount" => $totalCount]));
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

    public function displayPage(Request $request)
    {
        return $this->render('pages/staff_deliveries.html.twig');
    }
}