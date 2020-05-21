<?php

namespace App\Controller\Pages\Staff;

use App\Service\CourierOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CourierListController extends AbstractController
{

    private $courierDBOperator;
    private $session;

    public function __construct(CourierOperationService $courierDBOperator, SessionInterface $session)
    {
        $this->courierDBOperator = $courierDBOperator;
        $this->session = $session;
    }

    public function displayPage()
    {
        $warehouse = $this->session->get('currentWarehouse');
        $couriers = $this->courierDBOperator->getCouriersList($warehouse);
        return $this->render('pages/staff_courier_list.html.twig', [
            'couriers' => $couriers
        ]);
    }
}