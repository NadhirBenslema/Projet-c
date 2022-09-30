<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CoursRepository::class)
 */
class Cours
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("cours")
     * @Groups("posts:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\Length(
     *      min = 3,
     *      max = 10,
     * minMessage = "Le libelle doit composer au mois {{ limit }} caractères",
     * maxMessage = "Le libelle doit composer au plus {{ limit }} caractères"
     * )
     * @Groups("cours")
     * @Groups("posts:read")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     * minMessage = "La description doit composer au mois {{ limit }} caractères",
     * maxMessage = "La description doit composer au plus {{ limit }} caractères"
     * )
     * @Groups("cours")
     * @Groups("posts:read")
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Groups("cours")
     * @Groups("posts:read")
     */
    private $date;

     /**
     * @ORM\Column(type="integer")
     * @Groups("cours")
     * @Groups("posts:read")
     */
    private $frais;

    /**
     * @ORM\ManyToOne(targetEntity=NiveauDifficulte::class, inversedBy="courss")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_niveau", referencedColumnName="id")
     * })
     */
    private $id_niveau;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(?int $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getIdNiveau(): ?NiveauDifficulte
    {
        return $this->id_niveau;
    }

    public function setIdNiveau(?NiveauDifficulte $id_niveau): self
    {
        $this->id_niveau = $id_niveau;

        return $this;
    }
}
