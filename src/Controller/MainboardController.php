<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainboardController extends AbstractController
{
    public function displayPage()
    {
        return $this->render('pages/dashboard.html.twig');
    }
}