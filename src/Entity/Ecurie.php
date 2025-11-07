<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "ecurie")]
class Ecurie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 150, unique: true)]
    private string $nom;

    // marque du moteur (string)
    #[ORM\Column(type: "string", length: 100)]
    private string $moteur;

    #[ORM\OneToMany(mappedBy: "ecurie", targetEntity: Pilote::class, cascade: ["persist","remove"], orphanRemoval: true)]
    private Collection $pilotes;

    #[ORM\OneToMany(mappedBy: "ecurie", targetEntity: Infraction::class, cascade: ["persist","remove"])]
    private Collection $infractions;

    public function __construct()
    {
        $this->pilotes = new ArrayCollection();
        $this->infractions = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }

    public function getMoteur(): string { return $this->moteur; }
    public function setMoteur(string $moteur): self { $this->moteur = $moteur; return $this; }

    /** @return Collection|Pilote[] */
    public function getPilotes(): Collection { return $this->pilotes; }
    public function addPilote(Pilote $p): self {
        if (!$this->pilotes->contains($p)) {
            $this->pilotes->add($p);
            $p->setEcurie($this);
        }
        return $this;
    }
    public function removePilote(Pilote $p): self {
        if ($this->pilotes->removeElement($p)) {
            if ($p->getEcurie() === $this) {
                $p->setEcurie(null);
            }
        }
        return $this;
    }

    /** @return Collection|Infraction[] */
    public function getInfractions(): Collection { return $this->infractions; }
}
