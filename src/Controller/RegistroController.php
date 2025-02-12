<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Entity\Postulante;
use App\Form\EmpresaType;
use App\Form\PostulanteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RegistroController extends AbstractController
{
    #[Route('/registro/empresa', name: 'registro_empresa')]
    public function registrarEmpresa(Request $request, EntityManagerInterface $em): Response
    {
        $empresa = new Empresa();
        $form = $this->createForm(EmpresaType::class, $empresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($empresa);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render('registro/empresa.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/registro/postulante', name: 'registro_postulante')]
    public function registrarPostulante(Request $request, EntityManagerInterface $em): Response
    {
        $postulante = new Postulante();
        $form = $this->createForm(PostulanteType::class, $postulante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($postulante);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render('registro/postulante.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/registration', name: 'registration')]
    public function registro(): Response
    {
        return $this->render('registro/registro.html.twig');
    }
}
