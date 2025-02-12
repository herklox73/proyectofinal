<?php

namespace App\Entity;

use App\Repository\PostulacionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostulacionRepository::class)]
class Postulacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha_postulacion = null;

    #[ORM\Column(type: "string", length: 20, options: ["default" => "pendiente"])]
    #[Assert\Choice(choices: ["pendiente", "aceptado", "rechazado"], message: "Estado inválido")]
    private string $estado = 'pendiente';

    #[ORM\ManyToOne(inversedBy: 'oferta')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Postulante $postulante = null;

    #[ORM\ManyToOne(inversedBy: 'postulacions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OfertaLaboral $oferta = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaPostulacion(): ?\DateTimeInterface
    {
        return $this->fecha_postulacion;
    }

    public function setFechaPostulacion(\DateTimeInterface $fecha_postulacion): static
    {
        $this->fecha_postulacion = $fecha_postulacion;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getPostulante(): ?Postulante
    {
        return $this->postulante;
    }

    public function setPostulante(?Postulante $postulante): static
    {
        $this->postulante = $postulante;

        return $this;
    }

    public function getOferta(): ?OfertaLaboral
    {
        return $this->oferta;
    }

    public function setOferta(?OfertaLaboral $oferta): static
    {
        $this->oferta = $oferta;

        return $this;
    }
}
