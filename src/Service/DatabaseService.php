<?php

namespace App\Service;

use App\Entity\Receiver;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class DatabaseService
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function insert($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function insertMultiply(array $entities)
    {
        foreach ($entities as $entity)
        {
            $this->insert($entity);
        }
    }

    public function getUser($id): object
    {
        return $this->em->getRepository(User::class)->find($id);
    }

    public function updateUserPassword($id, $encodedPassword)
    {
        $user = $this->getUser($id);
        if (!$user)
        {
            throw new EntityNotFoundException();
        }
        $user->setPassword($encodedPassword);
        $this->em->flush();
    }

}