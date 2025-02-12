<?php

// src/Controller/SecurityController.php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface; 

class SecurityController extends AbstractController
{
    private $entityManager;

    // Inyectamos EntityManagerInterface en el constructor
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Obtener el último error de autenticación (si existe)
        $error = $authenticationUtils->getLastAuthenticationError();
        // Obtener el último nombre de usuario (correo)
        $lastUsername = $authenticationUtils->getLastUsername();
        
        // Definir el mensaje de error
        $errorMessage = null;

        // Verificar si hay error y si el correo existe
        if ($lastUsername) {
            // Aquí debes usar $this->entityManager en lugar de $this->getDoctrine()
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $lastUsername]);
            
            if ($user) {
                // El correo existe, pero la contraseña podría ser incorrecta
                if ($error) {
                    $errorMessage = "La contraseña ingresada es incorrecta.";
                }
            } else {
                // El correo no existe
                $errorMessage = "El correo ingresado no está registrado.";
            }
        }

        // Pasar el mensaje de error a la vista
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'error_message' => $errorMessage,
        ]);
    }
  
}
