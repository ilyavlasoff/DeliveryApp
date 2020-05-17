<?php

namespace App\Controller\Pages\Staff;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StaffHomeController extends AbstractController
{
    public function DisplayPage()
    {
        return $this->render('service_page_proto.html.twig', [

        ]);
    }
}