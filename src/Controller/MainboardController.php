<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainboardController extends AbstractController
{
    public function display()
    {
        return $this->render('pages/mainboard.html.twig');
    }
}