<?php

namespace App\Controller\Pages\Recipient;

use App\Service\ClientOperationService;
use App\Service\DeliveryOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipientDeliveriesController extends AbstractController
{
    private $deliveryDb, $clientDb;

    public function __construct(DeliveryOperationService $deliveryDb, ClientOperationService $clientDb)
    {
        $this->deliveryDb = $deliveryDb;
        $this->clientDb = $clientDb;
    }
    public function displayPage()
    {
        $client = $this->clientDb->getClientViaUser($this->getUser());
        return $this->render('pages/receiver_deliveries.html.twig', [
            'client' => $client
        ]);
    }
}