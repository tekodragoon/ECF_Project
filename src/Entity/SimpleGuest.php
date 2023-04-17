<?php

namespace App\Entity;

use App\Repository\SimpleGuestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SimpleGuestRepository::class)]
class SimpleGuest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column]
    private ?bool $adult = null;

    #[ORM\ManyToOne(inversedBy: 'simpleGuests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SimpleUser $simpleUser = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $allergies = null;

    public function __construct()
    {
        $this->adult = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function isAdult(): ?bool
    {
        return $this->adult;
    }

    public function setAdult(bool $adult): self
    {
        $this->adult = $adult;

        return $this;
    }

    public function getSimpleUser(): ?SimpleUser
    {
        return $this->simpleUser;
    }

    public function setSimpleUser(?SimpleUser $simpleUser): self
    {
        $this->simpleUser = $simpleUser;

        return $this;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function setAllergies(?string $allergies): self
    {
        $this->allergies = $allergies;

        return $this;
    }
}
