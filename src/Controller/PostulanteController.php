<?php

namespace App\Controller;

use App\Entity\Postulante;
use App\Entity\User;
use App\Form\PostulanteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/postulante')]
class PostulanteController extends AbstractController
{


    #[Route('/', name: 'postulante_dashboard')]
    public function dashboard(): Response
    {
        // Renderiza la vista principal para el postulante (index.html.twig)
        return $this->render('postulante/index.html.twig');
    }


    #[Route('/registro', name: 'app_postulante_registro', methods: ['GET', 'POST'])]
    public function registro(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $postulante = new Postulante();
        $form = $this->createForm(PostulanteType::class, $postulante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $cedula = $form->get('cedula')->getData();

            // Verificar si el email ya está registrado
            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($existingUser) {
                $this->addFlash('error', 'El email ya está en uso. Intente con otro.');
                return $this->redirectToRoute('app_postulante_registro');
            }

            // Validar que la cédula no esté duplicada
            $cedulaExistente = $em->getRepository(Postulante::class)->findOneBy(['cedula' => $cedula]);
            if ($cedulaExistente) {
                $form->get('cedula')->addError(new \Symfony\Component\Form\FormError('Esta cédula ya está registrada.'));
            } else {
                // Crear usuario asociado
                $user = new User();
                $user->setEmail($email);
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $user->setRoles(['ROLE_POSTULANTE']);
                $postulante->setUser($user);

                $em->persist($user);
                $em->persist($postulante);
                $em->flush();

                $this->addFlash('success', 'Registro exitoso. Ahora puede iniciar sesión.');

                return $this->render('postulante/index.html.twig');
            }
        }

        return $this->render('postulante/registro.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
