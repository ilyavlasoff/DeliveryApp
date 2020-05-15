<?php

namespace App\Controller;

use App\Entity\Warehouse;
use App\Service\DeliveryOperationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $page = $request->request->get('page');
        $count = $request->request->get('count');
        $fields = $request->request->get('fields');
        $sort = $request->request->get('sort');

        $warehouse = $session->get('currentWarehouse');
        //$converted = settype($warehouse, Warehouse::class);

        if (!is_numeric($page) || !is_numeric($count))
        {
            /**
             * TODO "Добавить исключение когда аргументы не числовые"
             */
        }
        $offset = $page * $count;
        try {
            $content = $this->deliveryDBOperator->getDeliveriesListInWarehouse($warehouse, $count, $offset, $fields, $sort);
        }
        catch (\Exception $ex)
        {
            return new JsonResponse(['error' => $ex->getMessage()]);
        }
        if (!empty($content))
        {
            $fields = [];
            foreach ($content[0] as $index => $item)
            {
                $fields[] = $index;
            }
            return new JsonResponse(json_encode(["fields" => $fields, "content" => $content]));
        }
        else
        {
            return new JsonResponse(json_encode(['fields' => [], 'content' => []]));
        }
    }

    public function displayPage()
    {
        return $this->render('pages/staff_deliveries.html.twig', [

        ]);
    }
}