<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        // Obtener todos los usuarios de la base de datos
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users, // Pasamos los usuarios a la vista
        ]);
    }

    #[Route('/user/{id}', name: 'user_profile')]
    public function show(int $id, UserRepository $userRepository): Response
    {
        // Buscar un usuario por su ID
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user, // Pasamos el usuario a la vista
        ]);
    }
}
