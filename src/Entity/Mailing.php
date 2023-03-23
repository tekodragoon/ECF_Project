<?php

namespace App\Entity;

use App\Repository\MailingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MailingRepository::class)]
#[UniqueEntity(fields: ['usermail'], message: 'forms.mail.unique')]

class Mailing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Email(message: 'forms.mail.usermail')]
    private ?string $usermail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsermail(): ?string
    {
        return $this->usermail;
    }

    public function setUsermail(string $usermail): self
    {
        $this->usermail = $usermail;

        return $this;
    }
}
