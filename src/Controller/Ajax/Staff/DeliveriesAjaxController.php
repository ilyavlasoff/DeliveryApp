<?php

namespace App\Controller\Ajax\Staff;

use App\Service\DeliveryOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DeliveriesAjaxController extends AbstractController
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

}