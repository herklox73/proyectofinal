<?php

namespace App\Controller;

use App\Entity\User;  
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;


class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/usuarios', name: 'ver_usuarios')]
public function verUsuarios(EntityManagerInterface $entityManager): Response
{
    // Obtén todos los usuarios
    $usuarios = $entityManager->getRepository(User::class)->findAll();

    return $this->render('usuarios/index.html.twig', [
        'usuarios' => $usuarios,
    ]);
}

}
