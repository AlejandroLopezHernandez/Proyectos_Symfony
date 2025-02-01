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
    private ?string $titulo = null;

    #[ORM\Column]
    private ?int $duracion = null;

    #[ORM\Column(length: 255)]
    private ?string $album = null;

    #[ORM\Column(length: 255)]
    private ?string $autor = null;

    #[ORM\Column]
    private ?int $likes = null;

    #[ORM\ManyToOne(inversedBy: 'canciones')]
    private ?Estilo $genero = null;

    /**
     * @var Collection<int, PlaylistCancion>
     */
    #[ORM\OneToMany(targetEntity: PlaylistCancion::class, mappedBy: 'cancion')]
    private Collection $playlistCanciones;

    /**
     * @var Collection<int, Usuario>
     */
    #[ORM\ManyToMany(targetEntity: Usuario::class, mappedBy: 'cancion')]
    private Collection $usuarios;

    public function __construct()
    {
        $this->playlistCanciones = new ArrayCollection();
        $this->usuarios = new ArrayCollection();
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

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

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

    public function getAlbum(): ?string
    {
        return $this->album;
    }

    public function setAlbum(string $album): static
    {
        $this->album = $album;

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

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): static
    {
        $this->likes = $likes;

        return $this;
    }

    public function getGenero(): ?Estilo
    {
        return $this->genero;
    }

    public function setGenero(?Estilo $genero): static
    {
        $this->genero = $genero;

        return $this;
    }

    /**
     * @return Collection<int, PlaylistCancion>
     */
    public function getPlaylistCanciones(): Collection
    {
        return $this->playlistCanciones;
    }

    public function addPlaylistCancione(PlaylistCancion $playlistCancione): static
    {
        if (!$this->playlistCanciones->contains($playlistCancione)) {
            $this->playlistCanciones->add($playlistCancione);
            $playlistCancione->setCancion($this);
        }

        return $this;
    }

    public function removePlaylistCancione(PlaylistCancion $playlistCancione): static
    {
        if ($this->playlistCanciones->removeElement($playlistCancione)) {
            // set the owning side to null (unless already changed)
            if ($playlistCancione->getCancion() === $this) {
                $playlistCancione->setCancion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Usuario>
     */
    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    public function addUsuario(Usuario $usuario): static
    {
        if (!$this->usuarios->contains($usuario)) {
            $this->usuarios->add($usuario);
            $usuario->addCancion($this);
        }

        return $this;
    }

    public function removeUsuario(Usuario $usuario): static
    {
        if ($this->usuarios->removeElement($usuario)) {
            $usuario->removeCancion($this);
        }

        return $this;
    }
}
