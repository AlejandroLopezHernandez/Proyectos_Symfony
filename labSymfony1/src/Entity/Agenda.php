<?php

namespace App\Entity;

use App\Repository\AgendaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgendaRepository::class)]
class Agenda
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    /**
     * @var Collection<int, Tarea>
     */
    #[ORM\OneToMany(targetEntity: Tarea::class, mappedBy: 'agenda')]
    private Collection $tarea;

    public function __construct()
    {
        $this->tarea = new ArrayCollection();
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

    /**
     * @return Collection<int, Tarea>
     */
    public function getTarea(): Collection
    {
        return $this->tarea;
    }

    public function addTarea(Tarea $tarea): static
    {
        if (!$this->tarea->contains($tarea)) {
            $this->tarea->add($tarea);
            $tarea->setAgenda($this);
        }

        return $this;
    }

    public function removeTarea(Tarea $tarea): static
    {
        if ($this->tarea->removeElement($tarea)) {
            // set the owning side to null (unless already changed)
            if ($tarea->getAgenda() === $this) {
                $tarea->setAgenda(null);
            }
        }

        return $this;
    }
}
