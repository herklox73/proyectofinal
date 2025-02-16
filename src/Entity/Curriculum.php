<?php

namespace App\Entity;

use App\Repository\CurriculumRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurriculumRepository::class)]
class Curriculum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255)]
    private ?string $archivo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha_subida = null;

    #[ORM\OneToOne(inversedBy: "curriculum", targetEntity: Postulante::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Postulante $postulante = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getArchivo(): ?string
    {
        return $this->archivo;
    }

    public function setArchivo(string $archivo): static
    {
        $this->archivo = $archivo;

        return $this;
    }

    public function getFechaSubida(): ?\DateTimeInterface
    {
        return $this->fecha_subida;
    }

    public function setFechaSubida(\DateTimeInterface $fecha_subida): static
    {
        $this->fecha_subida = $fecha_subida;

        return $this;
    }

    public function getPostulante(): ?Postulante
    {
        return $this->postulante;
    }

    public function setPostulante(Postulante $postulante): static
    {
        $this->postulante = $postulante;

        return $this;
    }
}
