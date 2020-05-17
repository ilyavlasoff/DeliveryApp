<?php

namespace App\Controller\Pages\Recipient;

use App\Form\ChangePasswordFormType;
use App\Form\ClientPrivateDataFormType;
use App\Service\ClientOperationService;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RecipientSettingsController extends AbstractController
{
    private $db;
    private $passwordEncoder;

    public function __construct(ClientOperationService $db, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->db = $db;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function changeClientData(FormInterface $form)
    {
        $changingParams = $form->getData();
        $client = $this->db->getClientViaUser($this->getUser());
        $m = $this->db->updateClientData($client->getId(), $changingParams);
        return $m;
    }

    public function getClientParams(): array
    {
        $clientParams = [];
        $user = $this->getUser();
        $client = $this->db->getClientViaUser($user);
        $clientParams['username'] = $user->getUsername();
        $clientParams['email'] = $user->getEmail();
        $clientParams['name'] = $client->getName();
        $clientParams['surname'] = $client->getSurname();
        $clientParams['patronymic'] = $client->getPatronymic();
        $clientParams['passport'] = $client->getPassport();
        $clientParams['phone'] = $client->getPhone();
        return $clientParams;
    }

    public function changePassword(FormInterface $form)
    {
        $data = $form->getData();
        $oldPassword = $data['oldPassword'];
        $newPassword = $data['newPassword'];
        $currentUser = $this->getUser();
        $validOldPassword = $this->passwordEncoder->isPasswordValid($currentUser, $oldPassword);
        if (!$validOldPassword)
        {
            throw new \Exception('Old password is incorrect');
        }
        try {
            $encoded = $this->passwordEncoder->encodePassword($currentUser, $newPassword);
            $this->db->updateUserPassword($currentUser->getId(), $encoded);
        }
        catch (\Exception $ex)
        {
            throw new \Exception('Unexpected error occured');
        }
        return true;
    }

    public function displayPage(ClientOperationService $db, Request $request)
    {
        $user = $this->getUser();
        $client = $db->getClientViaUser($user);

        $passwordChangeForm = $this->createForm(ChangePasswordFormType::class);
        $passwordChangeForm->handleRequest($request);

        $privateDataChangeForm = $this->createForm(ClientPrivateDataFormType::class);
        $clientStandardValues = $this->getClientParams();
        $privateDataChangeForm->setData($clientStandardValues);
        $privateDataChangeForm->handleRequest($request);

        $informMessages = [];
        if ($passwordChangeForm->isSubmitted() && $passwordChangeForm->isValid())
        {
            try
            {
                if ($this->changePassword($passwordChangeForm))
                {
                    $informMessages[] = 'Password successfully changed';
                };
            }
            catch (\Exception $ex)
            {
                $formError = new FormError($ex->getMessage());
                $passwordChangeForm->addError($formError);
            }
        }

        if ($privateDataChangeForm->isSubmitted())
        {
            try
            {
                if ($this->changeClientData($privateDataChangeForm))
                {
                    $informMessages[] = 'Password successfully changed';
                };
            }
            catch (\Exception $ex)
            {
                $formError = new FormError($ex->getMessage());
                $privateDataChangeForm->addError($formError);
            }
        }

        return $this->render('pages/receiver_settings.html.twig', [
            'client' => $client,
            'messages' => $informMessages,
            'changePasswordForm' => $passwordChangeForm->createView(),
            'privateDataChangeForm' => $privateDataChangeForm->createView()
        ]);
    }

}