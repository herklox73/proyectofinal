<?php

namespace App\Controller;

use App\Entity\OfertaLaboral;
use App\Entity\Empresa;
use App\Entity\User;
use App\Entity\Postulacion;
use App\Form\EmpresaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

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

            // Validación de email duplicado en User
            if ($em->getRepository(User::class)->findOneBy(['email' => $email])) {
                $this->addFlash('error', 'El email ya está en uso.');
                return $this->redirectToRoute('app_empresa_registro');
            }

            // Validación de RUC duplicado en Empresa
            if ($em->getRepository(Empresa::class)->findOneBy(['ruc' => $ruc])) {
                $this->addFlash('error', 'El RUC ya está registrado.');
                return $this->redirectToRoute('app_empresa_registro');
            }

            // Guardar empresa
            $em->persist($empresa);
            $em->flush();

            $this->addFlash('success', 'Registro exitoso.');
            return $this->redirectToRoute('empresa_dashboard');
        }

        return $this->render('empresa_c/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/dashboard', name: 'empresa_dashboard', methods: ['GET'])]
    public function dashboard(EntityManagerInterface $em): Response
    {
        // Obtener la empresa del usuario actual
        $user = $this->getUser();
        $empresa = $em->getRepository(Empresa::class)->findOneBy(['user' => $user]);

        if (!$empresa) {
            // Manejar caso cuando no hay empresa asociada
            throw $this->createNotFoundException('No se encontró la empresa asociada al usuario');
        }

        $ofertas = $em->getRepository(OfertaLaboral::class)->findBy(['empresa' => $empresa]);

        return $this->render('empresa_c/empresa.html.twig', [
            'ofertas' => $ofertas,
            'empresa' => $empresa
        ]);
    }

    #[Route('/get-postulantes/{id}', name: 'ver_postulantes', methods: ['GET'])]
    public function verPostulantes(int $id, EntityManagerInterface $em): Response
    {
        $oferta = $em->getRepository(OfertaLaboral::class)->find($id);
    
        if (!$oferta) {
            return $this->json(['error' => 'Oferta no encontrada'], Response::HTTP_NOT_FOUND);
        }
    
        $postulaciones = $em->getRepository(Postulacion::class)->findBy(['oferta' => $oferta]);
    
        $data = [];
        foreach ($postulaciones as $postulacion) {
            $postulante = $postulacion->getPostulante();
            $data[] = [
                'postulacionId' => $postulacion->getId(),
                'nombre' => $postulante->getNombres(),
                'descripcion' => $postulante->getCurriculum() ? $postulante->getCurriculum()->getDescripcion() : null,
                'curriculum' => $postulante->getCurriculum() ? $postulante->getCurriculum()->getArchivo() : null
            ];
        }
    
        return $this->json($data);
    }

    #[Route('/actualizar-foto', name: 'empresa_actualizar_foto', methods: ['POST'])]
    public function actualizarFotoPerfil(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $empresa = $this->getUser()->getEmpresa();

        $fotoPerfil = $request->files->get('fotoPerfil');
        
        if ($fotoPerfil) {
            $nombreSeguro = $slugger->slug(pathinfo($fotoPerfil->getClientOriginalName(), PATHINFO_FILENAME));
            $nuevoNombreArchivo = $nombreSeguro . '-' . uniqid() . '.' . $fotoPerfil->guessExtension();

            try {
                $fotoPerfil->move($this->getParameter('profile_directory'), $nuevoNombreArchivo);
                
                // Eliminar la foto anterior si existe
                if ($empresa->getFotoPerfil() && file_exists($this->getParameter('profile_directory') . '/' . $empresa->getFotoPerfil())) {
                    unlink($this->getParameter('profile_directory') . '/' . $empresa->getFotoPerfil());
                }

                $empresa->setFotoPerfil($nuevoNombreArchivo);
                $em->persist($empresa);
                $em->flush();

                $this->addFlash('success', 'Foto de perfil actualizada correctamente.');
            } catch (FileException $e) {
                $this->addFlash('error', 'Hubo un problema al subir la imagen.');
            }
        }

        return $this->redirectToRoute('empresa_dashboard');
    }
}
