<?php

namespace App\Controller;

use App\Service\DatabaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReceiverSettingsController extends AbstractController
{
    public function displayPage(DatabaseService $db)
    {
        return $this->render('pages/receiver_account.html.twig', [
        ]);
    }
}