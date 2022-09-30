<?php

namespace App\Entity;

use App\Repository\NiveauDifficulteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=NiveauDifficulteRepository::class)
 */
class NiveauDifficulte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("niveaudifficulte")
     * @Groups("posts:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\Length(
     *      min = 3,
     *      max = 10,
     * minMessage = "Le type doit composer au mois {{ limit }} caractères",
     * maxMessage = "Le type doit composer au plus {{ limit }} caractères"
     * )
     * @Groups("niveaudifficulte")
     * @Groups("posts:read")
     */
    private $niveau;

    

    /**
     * @ORM\OneToMany(targetEntity=Cours::class, mappedBy="id_niveau")
     */
    private $courss;

    public function __construct()
    {
        $this->courss = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(?string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    



    public function addCours(Cours $cours): self
    {
        if (!$this->courss->contains($cours)) {
            $this->courss[] = $cours;
            $cours->setIdNiveau($this);
        }

        return $this;
    }

    public function removeCours(Cours $cours): self
    {
        if ($this->courss->removeElement($cours)) {
            // set the owning side to null (unless already changed)
            if ($cours->getIdNiveau() === $this) {
                $cours->setIdNiveau(null);
            }
        }

        return $this;
    }
}
