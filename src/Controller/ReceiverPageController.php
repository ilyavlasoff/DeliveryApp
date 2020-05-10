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
        //var_dump($deliveries);
        $stat = $db->getDeliveriesStatistics($client);
        var_dump($stat);
        return $this->render('pages/receiver_account.html.twig', [
            'client' => $client
        ]);
    }
}