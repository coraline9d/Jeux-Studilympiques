<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    // #[Route('/')]
    // public function index(EntityManagerInterface $entityManager)
    // {
    //     $user = new User();

    //     $plainPassword = '';
    //     $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

    //     $user
    //         ->setEmail('')
    //         ->setPassword($hashedPassword)
    //         ->setRoles(["ROLE_ADMIN"])
    //         ->setFirstName('')
    //         ->setLastName('');

    //     $entityManager->persist($user);
    //     $entityManager->flush();

    //     return $this->render('user/index.html.twig', [
    //         'controller_name' => 'UserController',
    //     ]);
    // }
}

