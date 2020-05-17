<?php

namespace App\Controller;

use App\Service\ClientOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReceiverPageController extends AbstractController
{
    public function displayPage(ClientOperationService $db)
    {
        $user = $this->getUser();
        $client = $db->getClientViaUser($user);
        $deliveries = $db->getDeliveriesList($client, 5, 0);
        $stat = $db->getDeliveriesStatistics($client);
        return $this->render('pages/receiver_account.html.twig', [
            'client' => $client
        ]);
    }
}