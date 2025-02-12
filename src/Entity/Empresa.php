<?php

namespace App\Entity;

use App\Repository\EmpresaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmpresaRepository::class)]
class Empresa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "El nombre de la empresa es obligatorio.")]
    private ?string $nombreEmpresa = null;

    #[ORM\Column(length: 20, unique: true)]
    #[Assert\NotBlank(message: "El RUC es obligatorio.")]
    #[Assert\Length(min: 10, max: 20, minMessage: "El RUC debe tener al menos 10 caracteres.")]
    private ?string $ruc = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Debe indicar la persona encargada.")]
    private ?string $personaEncargada = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "El contacto es obligatorio.")]
    #[Assert\Regex(pattern: "/^\+?\d{7,15}$/", message: "El contacto debe ser un número válido.")]
    private ?string $contacto = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La ubicación es obligatoria.")]
    private ?string $ubicacion = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La dirección es obligatoria.")]
    private ?string $direccion = null;

    #[ORM\OneToOne(inversedBy: 'empresa', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: OfertaLaboral::class, mappedBy: 'empresa', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $ofertasLaborales;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $fotoPerfil = null;

    public function __construct()
    {
        $this->ofertasLaborales = new ArrayCollection();
    }

    // Métodos Getters y Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreEmpresa(): ?string
    {
        return $this->nombreEmpresa;
    }

    public function setNombreEmpresa(string $nombreEmpresa): static
    {
        $this->nombreEmpresa = $nombreEmpresa;
        return $this;
    }

    public function getRuc(): ?string
    {
        return $this->ruc;
    }

    public function setRuc(string $ruc): static
    {
        $this->ruc = $ruc;
        return $this;
    }

    public function getPersonaEncargada(): ?string
    {
        return $this->personaEncargada;
    }

    public function setPersonaEncargada(string $personaEncargada): static
    {
        $this->personaEncargada = $personaEncargada;
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

    public function getUbicacion(): ?string
    {
        return $this->ubicacion;
    }

    public function setUbicacion(string $ubicacion): static
    {
        $this->ubicacion = $ubicacion;
        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): static
    {
        $this->direccion = $direccion;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getFotoPerfil(): ?string
    {
        return $this->fotoPerfil;
    }

    public function setFotoPerfil(?string $fotoPerfil): static
    {
        $this->fotoPerfil = $fotoPerfil;
        return $this;
    }

    public function getOfertasLaborales(): Collection
    {
        return $this->ofertasLaborales;
    }

    public function addOfertaLaboral(OfertaLaboral $ofertaLaboral): static
    {
        if (!$this->ofertasLaborales->contains($ofertaLaboral)) {
            $this->ofertasLaborales->add($ofertaLaboral);
            $ofertaLaboral->setEmpresa($this);
        }

        return $this;
    }

    public function removeOfertaLaboral(OfertaLaboral $ofertaLaboral): static
    {
        if ($this->ofertasLaborales->removeElement($ofertaLaboral)) {
            if ($ofertaLaboral->getEmpresa() === $this) {
                $ofertaLaboral->setEmpresa(null);
            }
        }

        return $this;
    }
}
