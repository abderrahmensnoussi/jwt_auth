<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;


#[Route('/api', name: 'api_')]
class RegistrationController extends AbstractController
{

    #[Route('/registration', name: 'registration', methods: ['POST'])]
    public function index(
        Request $request,
        ManagerRegistry $doctrine,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());
        $email = $decoded->email;

        $userCheck = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($userCheck !== null) {
            return $this->json(['Error' => 'Email already exist']);
        }

        $plaintextPassword = $decoded->password;
        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setEmail($email);
        $user->setUsername($email);
        $user->setFirstname($decoded->firstname);
        $user->setLastname($decoded->lastname);
        $em->persist($user);
        $em->flush();

        return $this->json(['success' => 'User successfully created']);
    }
}
