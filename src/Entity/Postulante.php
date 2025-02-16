<?php

namespace App\Entity;
use App\Entity\User;
use App\Entity\Postulacion;
use App\Repository\PostulanteRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostulanteRepository::class)]
class Postulante
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombres = null;

    #[ORM\Column(length: 255)]
    private ?string $apellidos = null;

    #[ORM\Column(type: "string", length: 20)]
    #[Assert\Choice(choices: ["Masculino", "Femenino"], message: "Seleccione un género válido")]
    private string $genero;

    #[ORM\Column]
    private ?int $edad = null;

    #[ORM\Column(length: 255)]
    private ?string $direccion = null;

    #[ORM\Column(length: 20)]
    private ?string $cedula = null;

    #[ORM\Column(length: 20)]
    private ?string $telefono = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fotoPerfil = null;

    #[ORM\OneToOne(mappedBy: "postulante", targetEntity: Curriculum::class, cascade: ["persist", "remove"])]
    private ?Curriculum $curriculum = null;
    
    public function getCurriculum(): ?Curriculum
    {
        return $this->curriculum;
    }
    
    public function setCurriculum(Curriculum $curriculum): static
    {
        $this->curriculum = $curriculum;
        return $this;
    }
    
    #[ORM\OneToMany(mappedBy: 'postulante', targetEntity: Postulacion::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $postulaciones;

    
    #[ORM\OneToOne(inversedBy: "postulante", targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): static
    {
        $this->nombres = $nombres;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): static
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function setGenero(string $genero): static
    {
        $this->genero = $genero;

        return $this;
    }

    public function getEdad(): ?int
    {
        return $this->edad;
    }

    public function setEdad(int $edad): static
    {
        $this->edad = $edad;

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

    public function getCedula(): ?string
    {
        return $this->cedula;
    }

    public function setCedula(string $cedula): static
    {
        $this->cedula = $cedula;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): static
    {
        $this->telefono = $telefono;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }
// En tu clase Postulante

private ?string $email = null; // Asegúrate de que la propiedad esté definida

// Getter para la propiedad email
public function getEmail(): ?string
{
    return $this->email;
}

// Setter para la propiedad email
public function setEmail(string $email): self
{
    $this->email = $email;
    return $this;
}

}
