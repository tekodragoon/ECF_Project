<?php

namespace App\Entity;

use App\Repository\SimpleUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SimpleUserRepository::class)]
class SimpleUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $isRegistered = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $allergies = null;

    #[ORM\OneToMany(mappedBy: 'simpleUser', targetEntity: SimpleGuest::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $simpleGuests;

    public function __construct()
    {
        $this->isRegistered = false;
        $this->simpleGuests = new ArrayCollection();
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isIsRegistered(): ?bool
    {
        return $this->isRegistered;
    }

    public function setIsRegistered(bool $isRegistered): self
    {
        $this->isRegistered = $isRegistered;

        return $this;
    }

    /**
     * @return Collection<int, SimpleGuest>
     */
    public function getSimpleGuests(): Collection
    {
        return $this->simpleGuests;
    }

    public function addSimpleGuest(SimpleGuest $simpleGuest): self
    {
        if (!$this->simpleGuests->contains($simpleGuest)) {
            $this->simpleGuests->add($simpleGuest);
            $simpleGuest->setSimpleUser($this);
        }

        return $this;
    }

    public function removeSimpleGuest(SimpleGuest $simpleGuest): self
    {
        if ($this->simpleGuests->removeElement($simpleGuest)) {
            // set the owning side to null (unless already changed)
            if ($simpleGuest->getSimpleUser() === $this) {
                $simpleGuest->setSimpleUser(null);
            }
        }

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
