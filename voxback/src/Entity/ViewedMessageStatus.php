<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
#[ApiResource(
        paginationEnabled: false,
)]

/**
 * ViewedMessageStatus
 *
 * @ORM\Table(name="viewed_message_status", uniqueConstraints={@ORM\UniqueConstraint(name="view_message_status_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_viewed_message_user1_idx", columns={"user_id"}), @ORM\Index(name="fk_viewed_message_message1", columns={"message_id"})})
 * @ORM\Entity
 */
class ViewedMessageStatus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    private Uuid $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="read", type="string", length=255, nullable=true)
     */
    private $read;

    /**
     * @var Message
     *
     * @ORM\ManyToOne(targetEntity="Message")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     * })
     */
    private $message;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getRead(): ?string
    {
        return $this->read;
    }

    public function setRead(?string $read): self
    {
        $this->read = $read;

        return $this;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

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
