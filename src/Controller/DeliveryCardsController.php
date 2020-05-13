<?php

namespace App\Controller;

use App\Service\ClientOperationService;
use App\Service\DeliveryOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class DeliveryCardsController extends AbstractController
{
    private $deliveryDb;
    private $clientDb;

    public function __construct(DeliveryOperationService $deliveryDb,
                                ClientOperationService $clientDb)
    {
        $this->clientDb = $clientDb;
        $this->deliveryDb = $deliveryDb;
    }

    public function getItems(Request $request)
    {
        $client = $this->clientDb->getClientViaUser($this->getUser());

        $count = $request->request->get('needs');
        $offset = $request->request->get('offset');
        if (!(is_numeric($count) && is_numeric($offset)))
        {
            $count = 0;
            $offset = 0;
        }
        $displayAll = $request->request->get('displayAll');

        $deliveries = $this->clientDb->getDeliveriesList($client, $count, $offset, $displayAll);
        $totalCount = $this->clientDb->getDeliveriesStatistics($client);
        $loadedCount = count($deliveries);


        $content = $this->renderView('chunks/delivery_cards.html.twig', [
            'deliveries' => $deliveries
        ]);

        return new JsonResponse(json_encode(['totalCount' => $totalCount['cnt'], 'loadedCount' => $loadedCount,
            'content' => $content]));
    }
}