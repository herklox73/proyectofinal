<?php

namespace App\Controller;

use App\Entity\OfertaLaboral;
use App\Repository\OfertaLaboralRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfertaLaboralController extends AbstractController
{
    #[Route('/publicar-oferta', name: 'publicar_oferta', methods: ['POST'])]
    public function publicarOferta(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $usuario = $this->getUser();
        
        if (!$usuario) {
            return $this->json(['error' => 'Usuario no autenticado'], Response::HTTP_FORBIDDEN);
        }

        if (!$this->isGranted('ROLE_EMPRESA')) {
            return $this->json(['error' => 'No tienes permisos para publicar ofertas.'], Response::HTTP_FORBIDDEN);
        }

        if (!$usuario->getEmpresa()) {
            return $this->json(['error' => 'No tienes empresa asociada'], Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Datos inválidos'], Response::HTTP_BAD_REQUEST);
        }

        $requiredFields = ['cargo', 'tipo_contrato', 'canton', 'parroquia', 'remuneracion', 'jornada', 'areaEstudios', 'contacto'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return $this->json(['error' => "Falta el campo: $field"], Response::HTTP_BAD_REQUEST);
            }
        }

        $oferta = new OfertaLaboral();
        $oferta->setEmpresa($usuario->getEmpresa());
        $oferta->setCargo($data['cargo']);
        $oferta->setTipoContrato($data['tipo_contrato']);
        $oferta->setCanton($data['canton']);
        $oferta->setParroquia($data['parroquia']);
        $oferta->setRemuneracion((float) $data['remuneracion']);
        $oferta->setJornada($data['jornada']);
        $oferta->setAreaEstudios($data['areaEstudios']);
        $oferta->setContacto($data['contacto']);
        $oferta->setFechaPublicacion(new \DateTime());

        $em->persist($oferta);
        $em->flush();

        return $this->json([
            'message' => 'Oferta publicada con éxito',
            'oferta' => [
                'cargo' => $oferta->getCargo(),
                'tipo_contrato' => $oferta->getTipoContrato(),
                'canton' => $oferta->getCanton(),
                'parroquia' => $oferta->getParroquia(),
                'remuneracion' => $oferta->getRemuneracion(),
                'jornada' => $oferta->getJornada(),
                'areaEstudios' => $oferta->getAreaEstudios(),
                'contacto' => $oferta->getContacto(),
            ]
        ], Response::HTTP_OK);
    }

    #[Route('/eliminar-oferta/{id}', name: 'eliminar_oferta', methods: ['DELETE'])]
    public function eliminarOferta($id, EntityManagerInterface $em, OfertaLaboralRepository $repo): JsonResponse
    {
        $usuario = $this->getUser();

        if (!$usuario || !$this->isGranted('ROLE_EMPRESA')) {
            return $this->json(['error' => 'No tienes permisos para eliminar esta oferta'], Response::HTTP_FORBIDDEN);
        }

        $oferta = $repo->find($id);

        if (!$oferta) {
            return $this->json(['error' => 'Oferta no encontrada'], Response::HTTP_NOT_FOUND);
        }

        if ($oferta->getEmpresa() !== $usuario->getEmpresa()) {
            return $this->json(['error' => 'No puedes eliminar ofertas de otra empresa'], Response::HTTP_FORBIDDEN);
        }

        $em->remove($oferta);
        $em->flush();

        return $this->json(['message' => 'Oferta eliminada con éxito'], Response::HTTP_OK);
    }

    // Nuevo método para obtener la lista de ofertas
    #[Route('/get-ofertas', name: 'get_ofertas', methods: ['GET'])]
    public function getOfertas(OfertaLaboralRepository $ofertaRepository): JsonResponse
    {
        $ofertas = $ofertaRepository->findAll();

        $data = array_map(function ($oferta) {
            return [
                'id' => $oferta->getId(),
                'cargo' => $oferta->getCargo(),
                'tipo_contrato' => $oferta->getTipoContrato(),
                'canton' => $oferta->getCanton(),
                'parroquia' => $oferta->getParroquia(),
                'remuneracion' => $oferta->getRemuneracion(),
                'jornada' => $oferta->getJornada(),
                'areaEstudios' => $oferta->getAreaEstudios(),
                'contacto' => $oferta->getContacto(),
                'fecha_publicacion' => $oferta->getFechaPublicacion()->format('Y-m-d'),
            ];
        }, $ofertas);

        return $this->json($data, Response::HTTP_OK);
    }
}
