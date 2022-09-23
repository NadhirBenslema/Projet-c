<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
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
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Titre is required")
     * @Groups("cours")
     * @Groups("posts:read")
     */
    private $titre;

    /**
     *  @ORM\Column(type="string", length=255, nullable=false)
     *  @Assert\NotBlank(message="Charger une image")
     * @Assert\File(mimeTypes={"image/jpeg"})
     */
    private $imagec; 

    /**
     *  @ORM\Column(type="string", length=255, nullable=false)
     *  @Assert\NotBlank(message="description is required")
     * @Groups("cours")
     * @Groups("posts:read")
     */
    private $description;

    /**
     *  @ORM\Column(type="string", length=255, nullable=false)
     *  @Assert\NotBlank(message="filiere is required")
     * @Groups("cours")
     * @Groups("posts:read")
     */
    private $filiere;

       
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Name is required")
     * @Groups("cours")
     * @Groups("posts:read")
     */
    private $nomenseignant;

    /**
     * @ORM\Column(type="integer")
     * @Groups("cours")
     * @Groups("posts:read")
     */
    private $nbrheures;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="price is required")
     * @Groups("cours")
     * @Groups("posts:read")
     */
    private $prix;
    


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    

    public function getFiliere(): ?string
    {
        return $this->filiere;
    }

    public function setFiliere(?string $filiere): self
    {
        $this->filiere = $filiere;

        return $this;
    }

    public function getImagec()
    {
        return $this->imagec;
    }

    public function setImagec($imagec)
    {
        $this->imagec = $imagec;

        return $this;
    }


   

    

    public function getNomenseignant(): ?string
    {
        return $this->nomenseignant;
    }

    public function setNomenseignant(?string $nomenseignant): self
    {
        $this->nomenseignant = $nomenseignant;

        return $this;
    }

    public function getNbrheures()
    {
        return $this->nbrheures;
    }

    public function setNbrheures($nbrheures)
    {
        $this->nbrheures = $nbrheures;

        return $this;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }
}
