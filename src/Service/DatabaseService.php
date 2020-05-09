<?php

namespace App\Service;

use App\Entity\Receiver;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DatabaseService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getUser($id)
    {
        return $this->em->getRepository(User::class)->find($id);
    }

    public function getClientViaUser(UserInterface $user)
    {
        $userId = $user->getId();
        return $this->em->getRepository(Receiver::class)->findOneBy(['userId' => $userId]);
    }
}