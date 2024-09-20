<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;


#[ApiResource(paginationEnabled: false,)]
/**
 * history
 * @ORM\Table(name="history")
 * @ORM\Entity
 */
class History
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    private Uuid $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="app_user", referencedColumnName="id", onDelete="cascade", nullable=true)
     * })
     */
    private $user;

    /**
     * @var array|null
     *
     * @ORM\Column(name="changes", type="json", nullable=true)
     */
    private $changes;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    protected $date;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $action;

    /**
     * @var dataPoint
     *
     * @ORM\ManyToOne(targetEntity="dataPoint",cascade={"persist"}, inversedBy="history")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dataPoint", referencedColumnName="id", onDelete="cascade", nullable=true)
     * })
     */
    private $dataPoint;


    /**
     * @var MapFeature
     *
     * @ORM\ManyToOne(targetEntity="MapFeature",cascade={"persist"}, inversedBy="history")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="map_feature", referencedColumnName="id", onDelete="cascade", nullable=true)
     * })
     */
    private $mapFeature;

    public function __construct()
    {
        $this->date = new DateTime();
    }


    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAction(): ?int
    {
        return $this->action;
    }

    public function setAction(int $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getDataPoint(): ?dataPoint
    {
        return $this->dataPoint;
    }

    public function setDataPoint(?dataPoint $dataPoint): self
    {
        $this->dataPoint = $dataPoint;

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

    public function getChanges(): array
    {
        return $this->changes;
    }

    public function setChanges(?array $changes): self
    {
        $this->changes = $changes;

        return $this;
    }

    public function getMapFeature(): ?MapFeature
    {
        return $this->mapFeature;
    }

    public function setMapFeature(?MapFeature $mapFeature): self
    {
        $this->mapFeature = $mapFeature;

        return $this;
    }



}
