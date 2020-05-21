<?php

namespace App\Controller\Pages\Admin;

use App\Form\AutoFormType;
use App\Entity\Auto;
use App\Service\AutoOperationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AutoController extends AbstractController
{
    private $autoDBOperator;

    public function __construct(AutoOperationService $autoDBOperator)
    {
        $this->autoDBOperator = $autoDBOperator;
    }

    public function addPage(Request $request)
    {
        $auto = new Auto();
        $autoForm = $this->createForm(AutoFormType::class, $auto);
        $autoForm->handleRequest($request);

        if($autoForm->isSubmitted() && $autoForm->isValid())
        {
            $this->autoDBOperator->addNewAuto($auto);
            return new RedirectResponse($this->generateUrl('staff_auto_information', ['id' => $auto->getNumber()]));
        }

        return $this->render('pages/staff_add_auto.html.twig', [
            'autoForm' => $autoForm->createView()
        ]);
    }
}