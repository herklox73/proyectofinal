<?php

namespace App\Entity;

use App\Repository\OfertaLaboralRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfertaLaboralRepository::class)]
class OfertaLaboral
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cargo = null;

    #[ORM\Column(length: 50)]
    private ?string $tipo_contrato = null;

    #[ORM\Column(length: 100)]
    private ?string $canton = null;

    #[ORM\Column(length: 100)]
    private ?string $parroquia = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $remuneracion = null;

    #[ORM\Column(length: 50)]
    private ?string $jornada = null;

    #[ORM\Column(length: 255)]
    private ?string $area_estudios = null;

    #[ORM\Column(length: 20)]
    private ?string $contacto = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha_publicacion = null;

    #[ORM\ManyToOne(targetEntity: Empresa::class, inversedBy: 'ofertasLaborales')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Empresa $empresa = null;

    #[ORM\OneToMany(mappedBy: 'oferta', targetEntity: Postulacion::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $postulaciones;

    public function __construct()
    {
        $this->postulaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function setCargo(string $cargo): static
    {
        $this->cargo = $cargo;
        return $this;
    }

    public function getTipoContrato(): ?string
    {
        return $this->tipo_contrato;
    }
    
    public function setTipoContrato(string $tipo_contrato): static
    {
        $this->tipo_contrato = $tipo_contrato;
        return $this;
    }

    public function getCanton(): ?string
    {
        return $this->canton;
    }

    public function setCanton(string $canton): static
    {
        $this->canton = $canton;
        return $this;
    }

    public function getParroquia(): ?string
    {
        return $this->parroquia;
    }

    public function setParroquia(string $parroquia): static
    {
        $this->parroquia = $parroquia;
        return $this;
    }

    public function getRemuneracion(): ?string
    {
        return $this->remuneracion;
    }

    public function setRemuneracion(string $remuneracion): static
    {
        $this->remuneracion = $remuneracion;
        return $this;
    }

    public function getJornada(): ?string
    {
        return $this->jornada;
    }

    public function setJornada(string $jornada): static
    {
        $this->jornada = $jornada;
        return $this;
    }

    public function getAreaEstudios(): ?string
    {
        return $this->area_estudios;
    }

    public function setAreaEstudios(string $area_estudios): static
    {
        $this->area_estudios = $area_estudios;
        return $this;
    }

    public function getContacto(): ?string
    {
        return $this->contacto;
    }

    public function setContacto(string $contacto): static
    {
        $this->contacto = $contacto;
        return $this;
    }

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fecha_publicacion;
    }

    public function setFechaPublicacion(\DateTimeInterface $fecha_publicacion): static
    {
        $this->fecha_publicacion = $fecha_publicacion;
        return $this;
    }

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): static
    {
        $this->empresa = $empresa;
        return $this;
    }

    /**
     * @return Collection<int, Postulacion>
     */
    public function getPostulaciones(): Collection
    {
        return $this->postulaciones;
    }

    public function addPostulacion(Postulacion $postulacion): static
    {
        if (!$this->postulaciones->contains($postulacion)) {
            $this->postulaciones->add($postulacion);
            $postulacion->setOferta($this);
        }
        return $this;
    }

    public function removePostulacion(Postulacion $postulacion): static
    {
        if ($this->postulaciones->removeElement($postulacion)) {
            if ($postulacion->getOferta() === $this) {
                $postulacion->setOferta(null);
            }
        }
        return $this;
    }
}
