<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;


#[ApiResource(
    paginationEnabled: false
)]
/**
 * SubscriptionType
 *
 * @ORM\Table(name="subscription_type", uniqueConstraints={@ORM\UniqueConstraint(name="subscription_type_UNIQUE", columns={"id"})})
 * @ORM\Entity
 */
class SubscriptionType
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="storage_limit", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $storageLimit;

    /**
     * @var string|null
     *
     * @ORM\Column(name="subscription_fee", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $subscriptionFee;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStorageLimit(): ?string
    {
        return $this->storageLimit;
    }

    public function setStorageLimit(?string $storageLimit): self
    {
        $this->storageLimit = $storageLimit;

        return $this;
    }

    public function getSubscriptionFee(): ?string
    {
        return $this->subscriptionFee;
    }

    public function setSubscriptionFee(?string $subscriptionFee): self
    {
        $this->subscriptionFee = $subscriptionFee;

        return $this;
    }


}
