<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;

#[ApiResource(paginationEnabled: false,)]
/**
 * Report
 * @ORM\Table(name="report")
 * @ORM\Entity
 */
class Report
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
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;


    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;


    /**
     * @ORM\OneToMany(targetEntity="Media", mappedBy="report",orphanRemoval=true)
     */
    private $mediaFiles;


    /**
     * @var array|null
     *
     * @ORM\Column(name="json", type="json", nullable=true)
     */
    private $currentLocation;


    /**
     * @ORM\OneToMany(targetEntity="Chat", mappedBy="report")
     * @ORM\JoinColumn(name="chat_id", referencedColumnName="id")
     */
    private $chat;


    public function __construct()
    {
        $this->mediaFiles = new ArrayCollection();
        $this->chat = new ArrayCollection();
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


    public function getCurrentLocation(): ?array
    {
        return $this->currentLocation;
    }

    public function setCurrentLocation(?array $currentLocation): void
    {
        $this->currentLocation = $currentLocation;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }



    public function getId(): ?Uuid
    {
        return $this->id;
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
            $mediaFile->setReport($this);
        }

        return $this;
    }

    public function removeMediaFile(Media $mediaFile): self
    {
        if ($this->mediaFiles->removeElement($mediaFile)) {
            // set the owning side to null (unless already changed)
            if ($mediaFile->getReport() === $this) {
                $mediaFile->setReport(null);
            }
        }

        return $this;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chat->contains($chat)) {
            $this->chat->add($chat);
            $chat->setReport($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chat->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getReport() === $this) {
                $chat->setReport(null);
            }
        }

        return $this;
    }
}
