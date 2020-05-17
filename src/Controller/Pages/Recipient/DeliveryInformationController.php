<?php

namespace App\Controller\Pages\Recipient;

use App\Service\ClientOperationService;
use App\Service\DeliveryOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeliveryInformationController extends AbstractController
{
    private $clientDb;
    private $deliveryDb;

    public function __construct(ClientOperationService $clientDb, DeliveryOperationService $deliveryDb)
    {
        $this->clientDb = $clientDb;
        $this->deliveryDb = $deliveryDb;
    }

    public function displayPage(string $id)
    {
        $client = $this->clientDb->getClientViaUser($this->getUser());
        $delivery = $this->deliveryDb->getDeliveryById($id);
        $receiver = $delivery->getReceiver();
        $vendor = $delivery->getVendor();

        $lastStatus = $this->deliveryDb->getLastStatus($delivery->getId(), 1);
        $statusComment = $lastStatus[0]->getStatusComment();
        $statusSetDate = $lastStatus[0]->getStatusSetDate()->format('Y-m-d H:i:s');
        $daysFromStatusSet = (date_diff(new \DateTime(), new \DateTime($statusSetDate)))->format('%d');

        $deliveryContents = $this->deliveryDb->getDeliveryContents($delivery->getId());

        $payment = $this->deliveryDb->getPaymentInformationAboutDelivery($delivery->getId());

        return $this->render('pages/delivery_full_page.html.twig', [
            'client' => $client,
            'allowedView' => true,
            'receiver' => $receiver,
            'sender' => $vendor,
            'delivery' => $delivery,
            'daysFromStatusSet' => $daysFromStatusSet,
            'statusComment' => $statusComment,
            'deliveryContents' => $deliveryContents,
            'payment' => $payment
        ]);
    }
}