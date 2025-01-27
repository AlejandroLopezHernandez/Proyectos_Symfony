<?php

namespace App\Entity;

use App\Repository\CancionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CancionRepository::class)]
class Cancion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $autor = null;

    #[ORM\Column(length: 255)]
    private ?string $disco = null;

    #[ORM\Column(length: 255)]
    private ?string $genero = null;

    #[ORM\Column]
    private ?int $duracion = null;

    /**
     * @var Collection<int, Lista>
     */
    #[ORM\OneToMany(targetEntity: Lista::class, mappedBy: 'canciones')]
    private Collection $lista;

    public function __construct()
    {
        $this->lista = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAutor(): ?string
    {
        return $this->autor;
    }

    public function setAutor(string $autor): static
    {
        $this->autor = $autor;

        return $this;
    }

    public function getDisco(): ?string
    {
        return $this->disco;
    }

    public function setDisco(string $disco): static
    {
        $this->disco = $disco;

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

    public function getDuracion(): ?int
    {
        return $this->duracion;
    }

    public function setDuracion(int $duracion): static
    {
        $this->duracion = $duracion;

        return $this;
    }

    /**
     * @return Collection<int, Lista>
     */
    public function getLista(): Collection
    {
        return $this->lista;
    }

    public function addListum(Lista $listum): static
    {
        if (!$this->lista->contains($listum)) {
            $this->lista->add($listum);
            $listum->setCanciones($this);
        }

        return $this;
    }

    public function removeListum(Lista $listum): static
    {
        if ($this->lista->removeElement($listum)) {
            // set the owning side to null (unless already changed)
            if ($listum->getCanciones() === $this) {
                $listum->setCanciones(null);
            }
        }

        return $this;
    }
}
