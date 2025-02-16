<?php

namespace App\Controller;

use App\Entity\Postulante;
use App\Entity\User;
use App\Entity\OfertaLaboral;
use App\Entity\Postulacion;
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
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'postulante_dashboard')]
    public function dashboard(): Response
    {
        // Obtener todas las ofertas laborales
        $ofertas = $this->entityManager->getRepository(OfertaLaboral::class)->findAll();
    
        // Obtener el usuario autenticado
        $usuario = $this->getUser();
    
        // Inicializar postulaciones y postulante
        $postulaciones = [];
        $postulante = null;
    
        if ($usuario) {
            // Obtener el postulante asociado al usuario
            $postulante = $this->entityManager->getRepository(Postulante::class)->findOneBy(['user' => $usuario]);
    
            if ($postulante) {
                // Obtener todas las postulaciones del postulante
                $postulaciones = $this->entityManager->getRepository(Postulacion::class)->findBy(['postulante' => $postulante]);
            }
        }
    
        return $this->render('postulante/index.html.twig', [
            'ofertas' => $ofertas,
            'postulaciones' => $postulaciones, // Pasamos las postulaciones a la vista
            'postulante' => $postulante, // Pasamos el postulante a la vista
        ]);
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

                return $this->redirectToRoute('postulante_dashboard');
            }
        }

        return $this->render('postulante/registro.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/actualizar-foto', name: 'postulante_actualizar_foto', methods: ['POST'])]
    public function actualizarFoto(Request $request): Response
    {
        $postulante = $this->getUser()->getPostulante();
        
        $foto = $request->files->get('fotoPerfil');
        
        if ($foto) {
            $nombreArchivo = md5(uniqid()) . '.' . $foto->guessExtension();
            $foto->move(
                $this->getParameter('profile_directory'),
                $nombreArchivo
            );
            $postulante->setFotoPerfil($nombreArchivo);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('postulante_dashboard');
    }
}
