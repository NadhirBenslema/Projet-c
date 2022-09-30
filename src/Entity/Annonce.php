<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 */
class Annonce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("annonce")
     * @Groups("posts:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\Length(
     *      min = 3,
     *      max = 10,
     * minMessage = "Le nom doit composer au mois {{ limit }} caractères",
     * maxMessage = "Le nom doit composer au plus {{ limit }} caractères"
     * )
     * @Groups("annonce")
     * @Groups("posts:read")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\Length(
     *      min = 3,
     *      max = 10,
     * minMessage = "La description doit composer au mois {{ limit }} caractères",
     * maxMessage = "La description doit composer au plus {{ limit }} caractères"
     * )
     * @Groups("annonce")
     * @Groups("posts:read")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("annonce")
     * @Groups("posts:read")
     */
    private $image;

    

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }





   

    
}
