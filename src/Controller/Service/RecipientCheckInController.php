<?php

namespace App\Controller\Service;

use App\Entity\Receiver;
use App\Form\ReceiverFormType;
use App\Service\DatabaseService;
use App\Service\MailSender;
use App\Service\RandomCodeGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RecipientCheckInController extends AbstractController
{
    public function createRecipient(Request $request)
    {
        $user = $this->get('session')->get('preAuthenticatedUser');
        if (!$user)
        {
            return new RedirectResponse($this->generateUrl("app_register"));
        }
        $receiver = new Receiver();
        $form = $this->createForm(ReceiverFormType::class, $receiver);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $receiver->setPassport(str_replace([' '], [''], $receiver->getPassport()));
            $receiver->setPhone(preg_replace(['/[^\d]+/'], [''], $receiver->getPhone()));
            $receiver->setUserId($user);

            $this->get('session')->set('preRegisteredReceiver', $receiver);
            return new RedirectResponse($this->generateUrl('app_confirm'));
        }

        return $this->render("registration/reg_receiver.html.twig", [
            'regReceiverForm' => $form->createView()
        ]);
    }

    public function confirmRegistration(MailerInterface $mailer,
                                        Request $request)
    {
        $session = $this->get('session');
        $user = $session->get('preAuthenticatedUser');
        $receiver = $session->get('preRegisteredReceiver');
        if (!$user || !$receiver)
        {
            return new RedirectResponse($this->generateUrl("app_register"));
        }

        $form = $this->createFormBuilder()
            ->add('code', TextType::class, [
                'label' => 'Сonfirmation code',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Code can not be empty'
                    ]),
                    new Regex([
                        'pattern' => "/^[\da-f]+$/",
                        'message' => 'This is not a valid code format'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit'
            ])
            ->getForm();
        $form->handleRequest($request);

        $savedCode = $session->get('verificationCode');
        if ($form->isSubmitted() && $form->isValid() && $savedCode)
        {
            $data = $form->getData();
            if ($data['code'] === $savedCode)
            {
                $this->completeAuthorization($request);
                return new RedirectResponse($this->generateUrl('main_page'));
            }
            else
            {
                return $this->render('registration/confirmation.html.twig', [
                    'confirmationForm' => $form->createView(),
                    'errorCode' => true
                ]);
            }
        }

        $randomGenerator = new RandomCodeGenerator();
        $randomCode = $randomGenerator->generate($this->getParameter('randomCodeLength'));
        $session->set('verificationCode', $randomCode);

        $sendFrom = $this->getParameter('sendFromAddress');
        $mailSender = new MailSender($mailer);
        $mail = $mailSender->createConfirmation($sendFrom, $user->getEmail(), $randomCode);
        $mailSender->sendMessage($mail);

        return $this->render('registration/confirmation.html.twig', [
            'confirmationForm' => $form->createView(),
            'errorCode' => false
        ]);
    }

    public function completeAuthorization(Request $request, DatabaseService $db)
    {
        $session = $this->get('session');
        $user = $session->get('preAuthenticatedUser');
        $receiver = $session->get('preRegisteredReceiver');

        $db->insertMultiply([$user, $receiver]);

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);

        $this->get('session')->set('_security_main', serialize($token));
    }
}