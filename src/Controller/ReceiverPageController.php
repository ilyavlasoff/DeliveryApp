<?php

namespace App\Controller;

use App\Service\DatabaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReceiverPageController extends AbstractController
{
    public function displayPage(DatabaseService $db)
    {
        $user = $this->getUser();
        $client = $db->getClientViaUser($user);
        return $this->render('pages/receiver_account.html.twig', [
            'client' => $client
        ]);
    }
}