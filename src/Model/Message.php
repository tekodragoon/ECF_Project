<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Message
{
    #[Assert\NotBlank]
    private string $name;
    #[Assert\Email]
    private string $email;
    #[Assert\NotBlank]
    private string $subject;
    #[Assert\NotBlank]
    #[Assert\Length(max: 2000)]
    private string $content;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Message
     */
    public function setName(string $name): Message
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Message
     */
    public function setEmail(string $email): Message
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return Message
     */
    public function setSubject(string $subject): Message
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Message
     */
    public function setContent(string $content): Message
    {
        $this->content = $content;
        return $this;
    }

}