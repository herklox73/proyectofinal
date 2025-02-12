<?php

namespace App\Controller;

use App\Entity\OfertaLaboral;
use App\Form\OfertaLaboralType;
use App\Repository\OfertaLaboralRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/empresa/c/ofertas')]
class OfertaLaboralController extends AbstractController
{
    #[Route('/', name: 'empresa_ofertas_list')]
    public function index(OfertaLaboralRepository $ofertaLaboralRepository): Response
    {
        $usuario = $this->getUser();

        if (!$usuario || !$usuario->getEmpresa()) {
            $this->addFlash('error', 'Debes iniciar sesión y tener una empresa registrada para ver tus ofertas.');
            return $this->redirectToRoute('homepage');
        }

        $ofertas = $ofertaLaboralRepository->findBy(['empresa' => $usuario->getEmpresa()]);

        return $this->render('empresa/c/ofertas.html.twig', [
            'ofertas' => $ofertas,
        ]);
    }

    #[Route('/publicar-oferta', name: 'publicar_oferta', methods: ['POST'])]
    public function publicarOferta(Request $request, EntityManagerInterface $em): Response
    {
        $usuario = $this->getUser();

        if (!$usuario || !$usuario->getEmpresa()) {
            return $this->json(['error' => 'No tienes permisos para publicar una oferta'], Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['error' => 'Datos inválidos'], Response::HTTP_BAD_REQUEST);
        }

        $oferta = new OfertaLaboral();
        $oferta->setEmpresa($usuario->getEmpresa());
        $oferta->setCargo($data['cargo']);
        $oferta->setTipoContrato($data['tipoContrato']);
        $oferta->setCanton($data['canton']);
        $oferta->setParroquia($data['parroquia']);
        $oferta->setRemuneracion($data['remuneracion']);
        $oferta->setJornada($data['jornada']);
        $oferta->setAreaEstudios($data['areaEstudios']);
        $oferta->setContacto($data['contacto']);
        $oferta->setFechaPublicacion(new \DateTime());

        $em->persist($oferta);
        $em->flush();

        return $this->json([
            'message' => 'Oferta publicada con éxito',
            'id' => $oferta->getId(),
            'cargo' => $oferta->getCargo(),
            'tipoContrato' => $oferta->getTipoContrato(),
            'canton' => $oferta->getCanton(),
            'parroquia' => $oferta->getParroquia(),
            'remuneracion' => $oferta->getRemuneracion(),
            'jornada' => $oferta->getJornada(),
            'areaEstudios' => $oferta->getAreaEstudios(),
            'contacto' => $oferta->getContacto(),
        ]);
    }
}
