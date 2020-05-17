<?php

namespace App\Controller\Service;

use App\Entity\Receiver;
use App\Entity\User;
use App\Form\ReceiverFormType;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use App\Service\DatabaseService;
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
        if ($this->getUser())
        {
            return $this->redirectToRoute('main_page');
        }

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
            $user->addRole(user::CLIENT);

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
}
