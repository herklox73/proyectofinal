<?php

namespace App\Controller;

use App\Entity\OfertaLaboral;
use App\Entity\Empresa;
use App\Entity\User;
use App\Form\EmpresaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/empresa/c')]
class EmpresaCController extends AbstractController
{
    #[Route('', name: 'app_empresa_registro', methods: ['GET', 'POST'])]
    public function registro(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $empresa = new Empresa();
        $form = $this->createForm(EmpresaType::class, $empresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $ruc = $form->get('ruc')->getData();

            // Validación de email y RUC duplicado
            if ($em->getRepository(User::class)->findOneBy(['email' => $email])) {
                $this->addFlash('error', 'El email ya está en uso.');
                return $this->redirectToRoute('app_empresa_registro');
            }

            if ($em->getRepository(Empresa::class)->findOneBy(['ruc' => $ruc])) {
                $this->addFlash('error', 'El RUC ya está registrado.');
                return $this->redirectToRoute('app_empresa_registro');
            }

            // Guardar empresa
            $em->persist($empresa);
            $em->flush();

            $this->addFlash('success', 'Registro exitoso.');
            return $this->render('empresa_c/empresa.html.twig');
        }

        return $this->render('empresa_c/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/empresa', name: 'empresa_dashboard', methods: ['GET'])]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $ofertas = $em->getRepository(OfertaLaboral::class)->findAll();
        return $this->render('empresa_c/empresa.html.twig', [
            'ofertas' => $ofertas,
        ]);
    }

}
