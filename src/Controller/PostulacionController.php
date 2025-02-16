<?php

namespace App\Controller;

use App\Entity\Postulante;
use App\Entity\OfertaLaboral;
use App\Entity\Postulacion;
use App\Entity\Curriculum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class PostulacionController extends AbstractController
{
    #[Route('/postulante', name: 'postulante', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usuario = $this->getUser();
        $postulante = $usuario ? $usuario->getPostulante() : null;

        if (!$postulante) {
            $this->addFlash('error', 'Debes estar autenticado para ver postulaciones.');
            return $this->redirectToRoute('app_login');
        }

        $ofertas = $entityManager->getRepository(OfertaLaboral::class)->findAll();
        $postulaciones = $entityManager->getRepository(Postulacion::class)->findBy(['postulante' => $postulante]);

        return $this->render('postulante/index.html.twig', [
            'ofertas' => $ofertas,
            'postulaciones' => $postulaciones,
        ]);
    }

    #[Route('/postulacion/{ofertaId}', name: 'postulacion_nueva', methods: ['POST'])]
    public function nuevaPostulacion(Request $request, int $ofertaId, EntityManagerInterface $entityManager): Response
    {
        $usuario = $this->getUser();
        if (!$usuario || !$usuario->getPostulante()) {
            throw $this->createAccessDeniedException('No se encontró un postulante asociado con su cuenta.');
        }

        $postulante = $usuario->getPostulante();
        $oferta = $entityManager->getRepository(OfertaLaboral::class)->find($ofertaId);

        if (!$oferta) {
            throw $this->createNotFoundException('Oferta laboral no encontrada.');
        }

        // Verificar si el postulante ya aplicó a esta oferta
        $postulacionExistente = $entityManager->getRepository(Postulacion::class)->findOneBy([
            'postulante' => $postulante,
            'oferta' => $oferta
        ]);

        if ($postulacionExistente) {
            $this->addFlash('warning', 'Ya te has postulado a esta oferta.');
            return $this->redirectToRoute('postulante_dashboard');
        }

        // Procesar formulario manualmente
        $descripcion = $request->request->get('descripcion');
        $archivo = $request->files->get('curriculum');

        if (!$archivo) {
            $this->addFlash('error', 'Debes adjuntar un archivo de currículum.');
            return $this->redirectToRoute('postulante_dashboard');
        }

        // Validar el tipo de archivo (por seguridad)
        $extensionesPermitidas = ['pdf', 'doc', 'docx'];
        $extensionArchivo = $archivo->guessExtension();

        if (!in_array($extensionArchivo, $extensionesPermitidas)) {
            $this->addFlash('error', 'Formato de archivo no permitido. Solo se aceptan PDF y documentos de Word.');
            return $this->redirectToRoute('postulante_dashboard');
        }

        // Verificar si el postulante ya tiene un curriculum
        $curriculum = $postulante->getCurriculum();
        if (!$curriculum) {
            $curriculum = new Curriculum();
            $curriculum->setPostulante($postulante);
        }

        $curriculum->setDescripcion($descripcion);

        // Guardar el archivo correctamente
        $fileName = uniqid() . '.' . $extensionArchivo;
        $archivo->move($this->getParameter('curriculum_directory'), $fileName);

        // Eliminar archivo anterior si existe
        if ($curriculum->getArchivo()) {
            $oldFilePath = $this->getParameter('curriculum_directory') . '/' . $curriculum->getArchivo();
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        $curriculum->setArchivo($fileName);
        $curriculum->setFechaSubida(new \DateTime());

        $entityManager->persist($curriculum);

        // Crear nueva postulación
        $postulacion = new Postulacion();
        $postulacion->setOferta($oferta);
        $postulacion->setPostulante($postulante);
        $postulacion->setEstado('pendiente');
        $postulacion->setFechaPostulacion(new \DateTime());

        $entityManager->persist($postulacion);
        $entityManager->flush();

        $this->addFlash('success', '¡Postulación realizada exitosamente!');

        return $this->redirectToRoute('postulante_dashboard');
    }

    #[Route('/postulacion/aceptar/{id}', name: 'postulacion_aceptar', methods: ['POST'])]
    public function aceptarPostulacion(int $id, EntityManagerInterface $entityManager): Response
    {
        $postulacion = $entityManager->getRepository(Postulacion::class)->find($id);
    
        if (!$postulacion) {
            return $this->json(['success' => false, 'message' => 'Postulación no encontrada.'], 404);
        }
    
        $postulacion->setEstado('Aceptado');
        $entityManager->flush();
    
        return $this->json(['success' => true, 'ofertaId' => $postulacion->getOferta()->getId()]);
    }

    #[Route('/postulacion/rechazar/{id}', name: 'postulacion_rechazar', methods: ['POST'])]
    public function rechazarPostulacion(int $id, EntityManagerInterface $entityManager): Response
    {
        $postulacion = $entityManager->getRepository(Postulacion::class)->find($id);
    
        if (!$postulacion) {
            return $this->json(['success' => false, 'message' => 'Postulación no encontrada.'], 404);
        }
    
        $postulacion->setEstado('Rechazado');
        $entityManager->flush();
    
        return $this->json(['success' => true, 'ofertaId' => $postulacion->getOferta()->getId()]);
    }
}
