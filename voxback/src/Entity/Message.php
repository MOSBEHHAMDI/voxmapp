<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;

#[ApiFilter(OrderFilter::class, properties: ['sendDate' => 'ASC'])]
#[ApiResource(
    paginationEnabled: false,
)]
/**
 * Message
 *
 * @ORM\Table(name="message", uniqueConstraints={@ORM\UniqueConstraint(name="massage_id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_message_chat1_idx", columns={"chat"}), @ORM\Index(name="fk_message_user1_idx", columns={"sender"})})
 * @ORM\Entity
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    #[Groups(['Chat:create'])]
    private Uuid $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="message", type="text", length=65535, nullable=true)
     */
    #[Groups(['Chat:create'])]
    private $messageText;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="send_date", type="datetime", nullable=true)
     */
    #[Groups(['Chat:create'])]
    private $sendDate;

    /**
     * @var Chat
     *
     * @ORM\ManyToOne(targetEntity="Chat", inversedBy="messages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="chat", referencedColumnName="id", onDelete="cascade")
     * })
     */
    private $chat;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sender", referencedColumnName="id")
     * })
     */
    #[Groups(['Chat:create'])]
    private $sender;


    /**
     * @ORM\OneToMany(targetEntity="Media", mappedBy="message",orphanRemoval=true)
     */
    #[Groups(['Chat:create'])]
    private $mediaFiles;

    #[Groups(['Chat:create'])]
    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="seenMessages")
     */
    private $seenBy;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sendDate = new \DateTime();
        $this->mediaFiles = new ArrayCollection();
        $this->seenBy = new ArrayCollection();

    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getMessageText(): ?string
    {
        return $this->messageText;
    }

    public function setMessageText(?string $text): self
    {
        $this->messageText = $text;

        return $this;
    }


    public function getSendDate(): ?\DateTimeInterface
    {
        return $this->sendDate;
    }

    public function setSendDate(?\DateTimeInterface $sendDate): self
    {
        $this->sendDate = $sendDate;

        return $this;
    }



    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(?Chat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMediaFiles(): Collection
    {
        return $this->mediaFiles;
    }

    public function addMediaFile(Media $mediaFile): self
    {
        if (!$this->mediaFiles->contains($mediaFile)) {
            $this->mediaFiles->add($mediaFile);
            $mediaFile->setMessage($this);
        }

        return $this;
    }


    public function removeMediaFile(Media $mediaFile): self
    {
        if ($this->mediaFiles->removeElement($mediaFile)) {
            // set the owning side to null (unless already changed)
            if ($mediaFile->getMessage() === $this) {
                $mediaFile->setMessage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSeenBy(): Collection
    {
        return $this->seenBy;
    }

    public function addSeenBy(User $seenBy): self
    {
        if (!$this->seenBy->contains($seenBy)) {
            $this->seenBy->add($seenBy);
        }

        return $this;
    }

    public function removeSeenBy(User $seenBy): self
    {
        $this->seenBy->removeElement($seenBy);

        return $this;
    }



}
