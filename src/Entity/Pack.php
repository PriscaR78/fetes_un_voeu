<?php

namespace App\Entity;

use App\Repository\PackRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PackRepository::class)
 */
class Pack
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Merci de saisir un nom de pack")
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Merci de saisir une descrption")
     * @Assert\Length(max=300, maxMessage="La description ne doit pas dépasser 300 caractères")
     */
    private $description1;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(max=300, maxMessage="La description ne doit pas dépasser 300 caractères")
     */
    private $description2;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Merci de choisir une image")
     */
    private $image1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image2;

    /**
     * @ORM\Column(type="integer")
     *  @Assert\NotBlank(message="Merci de saisir un prix pour le pack")
     * @Assert\Type(type="integer", message="Le prix doit être un entier")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image3;

    public $image1File;
    public $image2File;
    public $image3File;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbResa;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription1(): ?string
    {
        return $this->description1;
    }

    public function setDescription1(string $description1): self
    {
        $this->description1 = $description1;

        return $this;
    }

    public function getDescription2(): ?string
    {
        return $this->description2;
    }

    public function setDescription2(?string $description2): self
    {
        $this->description2 = $description2;

        return $this;
    }

    public function getImage1(): ?string
    {
        return $this->image1;
    }

    public function setImage1(string $image1): self
    {
        $this->image1 = $image1;

        return $this;
    }

    public function getImage2(): ?string
    {
        return $this->image2;
    }

    public function setImage2(?string $image2): self
    {
        $this->image2 = $image2;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage3(): ?string
    {
        return $this->image3;
    }

    public function setImage3(?string $image3): self
    {
        $this->image3 = $image3;

        return $this;
    }

    public function getNbResa(): ?int
    {
        return $this->nbResa;
    }

    public function setNbResa(?int $nbResa): self
    {
        $this->nbResa = $nbResa;

        return $this;
    }
}
