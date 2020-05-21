<?php

namespace App\Controller\Ajax\Staff;

use App\Entity\Courier;
use App\Service\AutoOperationService;
use App\Service\CourierOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AutosAjaxController extends AbstractController
{
    private $autoDBOperator;
    private $courierDBOperator;

    public function __construct(AutoOperationService $autoDBOperator,
                                CourierOperationService $courierDBOperator)
    {
        $this->autoDBOperator = $autoDBOperator;
        $this->courierDBOperator = $courierDBOperator;
    }

    public function getAvailableList(Request $request)
    {
        /*$c = new Courier();
        $c->setDriveCat(['C', 'A']);
        $autoList = $this->autoDBOperator->getAutoForTimeAndCourier(
            new \DateTime(), new \DateTime(), $c);
        var_dump($autoList);
        return $this->render('pages/receiver_tracking.html.twig');*/
        $fromDateString = $request->request->get('from');
        $toDateString = $request->request->get('to');
        $courierId = $request->request->get('courierId');

        try {
            $fromDateArr = date_parse($fromDateString);
            $fromDate = new \DateTime(date('Y-m-d H:i:s', mktime($fromDateArr['hour'], $fromDateArr['minute'],
                $fromDateArr['second'], $fromDateArr['month'], $fromDateArr['day'], $fromDateArr['year'])));
            $toDateArr = date_parse($toDateString);
            $toDate = new \DateTime(date('Y-m-d H:i:s', mktime($toDateArr['hour'], $toDateArr['minute'],
                $toDateArr['second'], $toDateArr['month'], $toDateArr['day'], $toDateArr['year'])));

            if (! ($fromDate && $toDate))
            {
                throw new \Exception('Can not convert string to date');
            }
        }
        catch (\Exception $ex)
        {
            return new JsonResponse(json_encode(['error' => $ex->getMessage()]));
        }

        $courier = null;
        if ($courierId)
        {
            try
            {
                $courier = $this->courierDBOperator->getCourierById($courierId);
            }
            catch (Exception $ex)
            {
                return new JsonResponse(json_encode(['error' => $ex->getMessage()]));
            }
        }
        try {
            $autoList = $this->autoDBOperator->getAutoForTimeAndCourier($fromDate, $toDate, $courier);
        }
        catch (\Exception $ex)
        {
            return new JsonResponse(json_encode(['error' => $ex->getMessage()]));
        }
        return new JsonResponse(json_encode(['resp' => $autoList]));
    }
}