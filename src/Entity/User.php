<?php

namespace App\Entity;
use App\Entity\Postulante;
use App\Entity\Empresa;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: "usuarios")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    #[ORM\Column(type: "string")]
    private ?string $password = null;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $tipo = null;
    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Empresa::class, cascade: ['persist', 'remove'])]
    private ?Empresa $empresa = null;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Postulante::class, cascade: ['persist', 'remove'])]
    private ?Postulante $postulante = null;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        if ($this->tipo === 'empresa') {
            $roles[] = 'ROLE_EMPRESA';
        }
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): self
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email ?? '';
    }

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }

    public function getPostulante(): ?Postulante
    {
        return $this->postulante;
    }
    
    public function setPostulante(?Postulante $postulante): self
    {
        $this->postulante = $postulante;
        return $this;
    }

}
