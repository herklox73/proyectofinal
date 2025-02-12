<?php
// src/Controller/RegistrationController.php
namespace App\Controller;

use App\Entity\Empresa;
use App\Entity\Postulante;
use App\Entity\User;
use App\Form\EmpresaType;
use App\Form\PostulanteType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/registration')]
class RegistrationController extends AbstractController
{
    #[Route('', name: 'app_registro_combined', methods: ['GET', 'POST'])]
    public function combinedRegistration(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        FileUploader $fileUploader,
        SluggerInterface $slugger
    ): Response {
        $empresa = new Empresa();
        $empresaForm = $this->createForm(EmpresaType::class, $empresa);
        
        $postulante = new Postulante();
        $postulanteForm = $this->createForm(PostulanteType::class, $postulante);

        // Procesar formulario de Empresa
        $empresaForm->handleRequest($request);
        if ($empresaForm->isSubmitted() && $empresaForm->isValid()) {
            return $this->processEmpresa($empresa, $empresaForm, $postulanteForm, $em, $passwordHasher, $fileUploader, $slugger);
        }

        // Procesar formulario de Postulante
        $postulanteForm->handleRequest($request);
        if ($postulanteForm->isSubmitted() && $postulanteForm->isValid()) {
            return $this->processPostulante($postulante, $postulanteForm, $empresaForm, $em, $passwordHasher, $fileUploader, $slugger);
        }

        return $this->render('registration/combined.html.twig', [
            'empresaForm' => $empresaForm->createView(),
            'postulanteForm' => $postulanteForm->createView(),
        ]);
    }

    private function processEmpresa($empresa, $form, $postulanteForm, $em, $passwordHasher, $fileUploader, $slugger)
    {
        $email = $form->get('email')->getData();
        $ruc = $empresa->getRuc();

        // Validaciones
        if ($em->getRepository(User::class)->findOneBy(['email' => $email])) {
            $this->addFlash('error', 'El email ya está registrado');
            return $this->redirectToRoute('app_registro_combined');
        }
        
        if ($em->getRepository(Empresa::class)->findOneBy(['ruc' => $ruc])) {
            $form->get('ruc')->addError(new FormError('RUC ya registrado'));
            return $this->render('registration/combined.html.twig', [
                'empresaForm' => $form->createView(),
                'postulanteForm' => $postulanteForm->createView(),
            ]);
        }

        // Crear usuario y empresa
        $user = $this->createUser($form, $email, $passwordHasher, 'ROLE_EMPRESA');
        $empresa->setUser($user);

        // Manejar foto de perfil si el formulario la tiene
        if ($form->has('fotoPerfil')) {
            $this->handleFileUpload($form, $empresa, $fileUploader, $slugger);
        }

        $em->persist($user);
        $em->persist($empresa);
        $em->flush();

        $this->addFlash('success', 'Empresa registrada exitosamente!');
        return $this->redirectToRoute('homepage');
    }

    private function processPostulante($postulante, $form, $empresaForm, $em, $passwordHasher, $fileUploader, $slugger)
    {
        $email = $form->get('email')->getData();
        $cedula = $postulante->getCedula();

        // Validaciones
        if ($em->getRepository(User::class)->findOneBy(['email' => $email])) {
            $this->addFlash('error', 'El email ya está registrado');
            return $this->redirectToRoute('app_registro_combined');
        }
        
        if ($em->getRepository(Postulante::class)->findOneBy(['cedula' => $cedula])) {
            $form->get('cedula')->addError(new FormError('Cédula ya registrada'));
            return $this->render('registration/combined.html.twig', [
                'empresaForm' => $empresaForm->createView(),
                'postulanteForm' => $postulanteForm->createView(),
            ]);
        }

        // Crear usuario y postulante
        $user = $this->createUser($form, $email, $passwordHasher, 'ROLE_POSTULANTE');
        $postulante->setUser($user);

        // Manejar foto de perfil si el formulario la tiene
        if ($form->has('fotoPerfil')) {
            $this->handleFileUpload($form, $postulante, $fileUploader, $slugger);
        }

        $em->persist($user);
        $em->persist($postulante);
        $em->flush();

        $this->addFlash('success', 'Postulante registrado exitosamente!');
        return $this->redirectToRoute('homepage');
    }

    private function createUser($form, $email, $passwordHasher, $role)
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            )
        );
        $user->setRoles([$role]);
        return $user;
    }

    private function handleFileUpload($form, $entity, $fileUploader, $slugger)
    {
        if (!$form->has('fotoPerfil')) {
            return; // Evita el error si el formulario no tiene fotoPerfil
        }

        $fotoPerfil = $form->get('fotoPerfil')->getData();
        if ($fotoPerfil) {
            $newFilename = $fileUploader->upload($fotoPerfil, $slugger);
            $entity->setFotoPerfil($newFilename);
        }
    }
}
