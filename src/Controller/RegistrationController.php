<?php

namespace App\Controller;

use App\Entity\Receiver;
use App\Entity\User;
use App\Form\ReceiverFormType;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use App\Service\MailSender;
use App\Service\RandomCodeGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class RegistrationController extends AbstractController
{
    private $user;

    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler,
                             UserAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->get('session')->set('preAuthenticatedUser', $user);

            return new RedirectResponse($this->generateUrl("receiver_register"));

            // do anything else you need here, like send an email

            /*return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );*/

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    public function createReceiver(Request $request)
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

            $receiver = $this->get('session')->set('preRegisteredReceiver', $receiver);

            /*$entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->persist($receiver);
            $entityManager->flush();*/

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

    public function completeAuthorization(Request $request)
    {
        $session = $this->get('session');
        $user = $session->get('preAuthenticatedUser');
        $receiver = $session->get('preRegisteredReceiver');/*The user needs to be registered */;#
        // Example of how to obtain an user:
        //$user = $this->getDoctrine()->getManager()->getRepository("AppBundle/Entity/User")->findOneBy(array('username' => "some user name example"));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->persist($receiver);
        $entityManager->flush();

        //Handle getting or creating the user entity likely with a posted form
        // The third parameter "main" can change according to the name of your firewall in security.yml
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);

        // If the firewall name is not main, then the set value would be instead:
        // $this->get('session')->set('_security_XXXFIREWALLNAMEXXX', serialize($token));
        $this->get('session')->set('_security_main', serialize($token));

        // Fire the login event manually
        //$event = new InteractiveLoginEvent($request, $token);
        //$this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

        /*
         * Now the user is authenticated !!!!
         * Do what you need to do now, like render a view, redirect to route etc.
         */
    }
}
