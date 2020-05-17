<?php

namespace App\Controller;

use App\Service\ClientOperationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainboardController extends AbstractController
{
    private $clientDb;

    public function __construct(ClientOperationService $clientDb)
    {
        $this->clientDb = $clientDb;
    }

    public function displayPage(Request $request)
    {
        $client = $this->clientDb->getClientViaUser($this->getUser());

        return $this->render('pages/dashboard.html.twig', [
            'client' => $client
        ]);
    }
}