<?php

namespace App\Controller\Pages\Recipient;

use App\Service\ClientOperationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipientMainboardController extends AbstractController
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