<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;


#[Route('/api', name: 'api_')]
class UsersController extends AbstractController
{

    #[Route('/users', name: 'get_users', methods: ['GET'])]
    public function getUsers(
        ManagerRegistry $doctrine,
    ): JsonResponse {
        $em = $doctrine->getManager();
        $users = $em->getRepository(User::class)->findAll();

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'email' => $user->getEmail(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
            ];
        } 

        return $this->json(['users' => $data]);
    }
}
