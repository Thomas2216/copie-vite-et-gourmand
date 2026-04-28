<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: "commande_stats")]
class CommandeStat
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: "string")]
    private string $menuTitre;

    #[ODM\Field(type: "int")]
    private int $nombrePersonnes;

    #[ODM\Field(type: "float")]
    private float $prixTotal;

    #[ODM\Field(type: "string")]
    private string $periode;

    #[ODM\Field(type: "date")]
    private \DateTime $dateCommande;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMenuTitre(): string
    {
        return $this->menuTitre;
    }

    public function setMenuTitre(string $menuTitre): self
    {
        $this->menuTitre = $menuTitre;
        return $this;
    }

    public function getNombrePersonnes(): int
    {
        return $this->nombrePersonnes;
    }

    public function setNombrePersonnes(int $nombrePersonnes): self
    {
        $this->nombrePersonnes = $nombrePersonnes;
        return $this;
    }

    public function getPrixTotal(): float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(float $prixTotal): self
    {
        $this->prixTotal = $prixTotal;
        return $this;
    }

    public function getPeriode(): string
    {
        return $this->periode;
    }

    public function setPeriode(string $periode): self
    {
        $this->periode = $periode;
        return $this;
    }

    public function getDateCommande(): \DateTime
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTime $dateCommande): self
    {
        $this->dateCommande = $dateCommande;
        return $this;
    }
}
