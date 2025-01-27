<?php
namespace App\Entity;

use App\Repository\EstiloRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstiloRepository::class)]
class Estilo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\ManyToOne(targetEntity: Perfil::class, inversedBy: 'estiloMusicalPreferido')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Perfil $perfil = null;

    /**
     * @var Collection<int, Cancion>
     */
    #[ORM\OneToMany(mappedBy: 'genero', targetEntity: Cancion::class, cascade: ['persist', 'remove'])]
    private Collection $cancion;

    public function __construct()
    {
        $this->cancion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getPerfil(): ?Perfil
    {
        return $this->perfil;
    }

    public function setPerfil(?Perfil $perfil): static
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * @return Collection<int, Cancion>
     */
    public function getCancion(): Collection
    {
        return $this->cancion;
    }

    public function addCancion(Cancion $cancion): static
    {
        if (!$this->cancion->contains($cancion)) {
            $this->cancion->add($cancion);
            $cancion->setGenero($this);
        }

        return $this;
    }

    public function removeCancion(Cancion $cancion): static
    {
        if ($this->cancion->removeElement($cancion)) {
            // set the owning side to null (unless already changed)
            if ($cancion->getGenero() === $this) {
                $cancion->setGenero(null);
            }
        }

        return $this;
    }
}
