<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "pilote")]
class Pilote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 100)]
    private string $prenom;

    #[ORM\Column(type: "string", length: 100)]
    private string $nom;

    // points licence (default 12)
    #[ORM\Column(type: "integer")]
    private int $points = 12;

    // date de début en F1
    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTimeInterface $dateStart = null;

    // statut: "actif", "reserve", "suspendu"
    #[ORM\Column(type: "string", length: 50)]
    private string $statut = 'actif';

    #[ORM\ManyToOne(targetEntity: Ecurie::class, inversedBy: "pilotes")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ecurie $ecurie = null;

    #[ORM\OneToMany(mappedBy: "pilote", targetEntity: Infraction::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $infractions;

    public function __construct()
    {
        $this->infractions = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $p): self { $this->prenom = $p; return $this; }
    public function getNom(): string { return $this->nom; }
    public function setNom(string $n): self { $this->nom = $n; return $this; }
    public function getPoints(): int { return $this->points; }
    public function setPoints(int $p): self { $this->points = $p; return $this; }
    public function getDateStart(): ?\DateTimeInterface { return $this->dateStart; }
    public function setDateStart(?\DateTimeInterface $d): self { $this->dateStart = $d; return $this; }
    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $s): self { $this->statut = $s; return $this; }
    public function getEcurie(): ?Ecurie { return $this->ecurie; }
    public function setEcurie(?Ecurie $e): self { $this->ecurie = $e; return $this; }

    /** @return Collection|Infraction[] */
    public function getInfractions(): Collection { return $this->infractions; }
    public function addInfraction(Infraction $i): self {
        if (!$this->infractions->contains($i)) {
            $this->infractions->add($i);
            $i->setPilote($this);
        }
        return $this;
    }
    public function removeInfraction(Infraction $i): self {
        if ($this->infractions->removeElement($i)) {
            if ($i->getPilote() === $this) {
                $i->setPilote(null);
            }
        }
        return $this;
    }
}
