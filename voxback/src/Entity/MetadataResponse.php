<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * MetadataResponse
 *
 * @ORM\Table(name="metadata_response", indexes={@Doctrine\ORM\Mapping\Index(name="fk_metadata_response_metadata1_idx", columns={"metadata"})})
 * @ORM\Entity
 */
class MetadataResponse
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
     * @ORM\Column(name="value", type="text", nullable=true)
     *
     */
    private $value;

    /**
     * @var Media
     *
     * @ORM\OneToOne(targetEntity="Media",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="media", referencedColumnName="id", onDelete="cascade")
     * })
     */
    private $image;

    /**
     * @var Metadata
     *
     * @ORM\ManyToOne(targetEntity="Metadata")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="metadata", referencedColumnName="id", onDelete="cascade")
     * })
     *
     */
    private $metadata;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->value ? : "";
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return Metadata
     */
    public function getMetadata(): ?Metadata
    {
        return $this->metadata;
    }

    /**
     * @param Metadata $metadata
     */
    public function setMetadata(Metadata $metadata): void
    {
        $this->metadata = $metadata;
    }


    /**
     * @return Media
     */
    public function getImage(): ?Media
    {
        return $this->image;
    }

    /**
     * @param Media $image
     * @return MetadataResponse
     */
    public function setImage(Media $image): MetadataResponse
    {
        $this->image = $image;
        return $this;
    }

}
