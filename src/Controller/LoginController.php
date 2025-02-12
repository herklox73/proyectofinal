<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si el usuario ya está autenticado, redirigirlo según su rol
        if ($this->getUser()) {
            return $this->redirectAfterLogin();
        }

        // Obtener el error de autenticación si existe
        $error = $authenticationUtils->getLastAuthenticationError();
        // Obtener el último nombre de usuario ingresado
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Este método debe estar vacío, Symfony maneja el logout automáticamente.');
    }

    private function redirectAfterLogin(): RedirectResponse
    {
        $user = $this->getUser();

        if (in_array('ROLE_POSTULANTE', $user->getRoles())) {
            return $this->redirectToRoute('postulante_index'); // Asegúrate de que esta ruta existe
        }

        if (in_array('ROLE_EMPRESA', $user->getRoles())) {
            return $this->redirectToRoute('empresa_c'); // Asegúrate de que esta ruta existe
        }

        return $this->redirectToRoute('homepage'); // Si no tiene roles específicos, va al inicio
    }
}
