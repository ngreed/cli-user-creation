<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(name="firstname", type="string", length=50, nullable=true)
     */
    private ?string $firstName;

    /**
     * @ORM\Column(name="lastname", type="string", length=50, nullable=true)
     */
    private ?string $lastName;

    /**
     * @ORM\Column(name="email", type="string", length=80)
     */
    private string $email;

    /**
     * @ORM\Column(name="phonenumber1", type="string", length=30, nullable=true)
     */
    private ?string $phoneNumber1;

    /**
     * @ORM\Column(name="phonenumber2", type="string", length=30, nullable=true)
     */
    private ?string $phoneNumber2;

    /**
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private ?string $comment;

    /**
     * @ORM\Column(name="doc", type="datetime", length=255)
     */
    private \DateTime $doc;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     *
     * @return self
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     *
     * @return self
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber1(): ?string
    {
        return $this->phoneNumber1;
    }

    /**
     * @param string|null $phoneNumber1
     *
     * @return self
     */
    public function setPhoneNumber1(?string $phoneNumber1): self
    {
        $this->phoneNumber1 = $phoneNumber1;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber2(): ?string
    {
        return $this->phoneNumber2;
    }

    /**
     * @param string|null $phoneNumber2
     *
     * @return self
     */
    public function setPhoneNumber2(?string $phoneNumber2): self
    {
        $this->phoneNumber2 = $phoneNumber2;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     *
     * @return self
     */
    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDoc(): \DateTime
    {
        return $this->doc;
    }

    /**
     * @param \DateTime $doc
     *
     * @return self
     */
    public function setDoc(\DateTime $doc): self
    {
        $this->doc = $doc;

        return $this;
    }
}