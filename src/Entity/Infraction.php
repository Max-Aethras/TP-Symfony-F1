<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "infraction")]
class Infraction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    // type: 'penalite' ou 'amende' ou 'mixte'
    #[ORM\Column(type: "string", length: 20)]
    private string $type; // penalite | amende | mixte

    // points retirés (nullable si amende)
    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $points = null;

    // montant en euros (nullable si pénalité)
    #[ORM\Column(type: "decimal", precision: 10, scale: 2, nullable: true)]
    private ?string $montant = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $description;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $dateInfraction;

    #[ORM\Column(type: "string", length: 150, nullable: true)]
    private ?string $nomCourse = null;

    // infraction peut concerner un pilote OU une écurie (ou les deux)
    #[ORM\ManyToOne(targetEntity: Pilote::class, inversedBy: "infractions")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Pilote $pilote = null;

    #[ORM\ManyToOne(targetEntity: Ecurie::class, inversedBy: "infractions")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Ecurie $ecurie = null;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->dateInfraction = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getType(): string { return $this->type; }
    public function setType(string $t): self { $this->type = $t; return $this; }
    public function getPoints(): ?int { return $this->points; }
    public function setPoints(?int $p): self { $this->points = $p; return $this; }
    public function getMontant(): ?string { return $this->montant; }
    public function setMontant(?string $m): self { $this->montant = $m; return $this; }
    public function getDescription(): string { return $this->description; }
    public function setDescription(string $d): self { $this->description = $d; return $this; }
    public function getDateInfraction(): \DateTimeInterface { return $this->dateInfraction; }
    public function setDateInfraction(\DateTimeInterface $d): self { $this->dateInfraction = $d; return $this; }
    public function getNomCourse(): ?string { return $this->nomCourse; }
    public function setNomCourse(?string $n): self { $this->nomCourse = $n; return $this; }
    public function getPilote(): ?Pilote { return $this->pilote; }
    public function setPilote(?Pilote $p): self { $this->pilote = $p; return $this; }
    public function getEcurie(): ?Ecurie { return $this->ecurie; }
    public function setEcurie(?Ecurie $e): self { $this->ecurie = $e; return $this; }
    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
}
