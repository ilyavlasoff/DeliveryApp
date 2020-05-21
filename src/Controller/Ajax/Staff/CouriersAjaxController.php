<?php

namespace App\Controller\Ajax\Staff;

use App\Service\AutoOperationService;
use App\Service\CourierOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CouriersAjaxController extends AbstractController
{
    private $courierDBOperator;
    private $autoDBOperator;

    public function __construct(CourierOperationService $courierDBOperator, AutoOperationService $autoDBOperator)
    {
        $this->courierDBOperator = $courierDBOperator;
        $this->autoDBOperator = $autoDBOperator;
    }

    public function getAvailableList(Request $request)
    {
        $fromDateString = $request->request->get('from');
        $toDateString = $request->request->get('to');
        $autoNum = $request->request->get('autoNum');

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


        $auto = null;
        if ($autoNum)
        {
            try
            {
                $auto = $this->autoDBOperator->getAutoViaStateNumber($autoNum);
            }
            catch (\Exception $ex)
            {
                return new JsonResponse(json_encode(['error' => $ex->getMessage()]));
            }
        }

        try {
            $autoList = $this->courierDBOperator->getAvailableCouriersList($fromDate, $toDate, $auto);
        }
        catch (\Exception $ex)
        {
            return new JsonResponse(json_encode(['error' => $ex->getMessage()]));
        }
        return new JsonResponse(json_encode(['resp' => $autoList]));
    }


}