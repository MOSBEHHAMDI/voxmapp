<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
#[ApiResource(
        paginationEnabled: false,
)]

/**
 * UserInteraction
 *
 * @ORM\Table(name="user_interaction")
 * @ORM\Entity
 */
class UserInteraction
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    private Uuid $id;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="interaction_date", type="datetime", nullable=true)
     */
    private $interactionDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="message", type="text", length=65535, nullable=true)
     */
    private $message;

    /**
     * @var InteractionType
     *
     * @ORM\ManyToOne(targetEntity="InteractionType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="interaction_type", referencedColumnName="id")
     * })
     */
    private $interactionType;

    /**
     * @var Task
     *
     * @ORM\ManyToOne(targetEntity="Task")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task", referencedColumnName="id")
     * })
     */
    private $task;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getInteractionDate(): ?\DateTimeInterface
    {
        return $this->interactionDate;
    }

    public function setInteractionDate(?\DateTimeInterface $interactionDate): self
    {
        $this->interactionDate = $interactionDate;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getInteractionType(): ?InteractionType
    {
        return $this->interactionType;
    }

    public function setInteractionType(?InteractionType $interactionType): self
    {
        $this->interactionType = $interactionType;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


}
