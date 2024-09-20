<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
#[ApiResource(
    paginationEnabled: false,

)]

/**
 * Polygone
 *
 * @ORM\Table(name="polygone", uniqueConstraints={@ORM\UniqueConstraint(name="polygone_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_polygone_user1_idx", columns={"user_id"})})
 * @ORM\Entity
 */
class Polygone
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    private Uuid $id;

    /**
     * @var array|null
     *
     * @ORM\Column(name="json", type="json", nullable=true)
     */
    private $json;

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

    public function getJson(): array
    {
        return $this->json;
    }

    public function setJson(?array $json): self
    {
        $this->json = $json;

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
