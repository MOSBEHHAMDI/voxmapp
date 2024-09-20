<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    paginationEnabled: false,
)]
/**
 * Media
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="media", uniqueConstraints={@ORM\UniqueConstraint(name="media_id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_media_media_type1_idx", columns={"media_type"}), @ORM\Index(name="fk_media_message1_idx", columns={"message_id"}), @ORM\Index(name="fk_media_data_point1_idx", columns={"data_point_id"})})
 * @ORM\Entity
 */
class Media
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */

    private Uuid $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=true)
     */
    private $creationDate;


    /**
     * @ORM\Column(type="datetime",name="update_date",nullable=true,)
     */
    protected $updateDate;

    /**
     * @var string|null
     * Media name (can be different from filename)
     * @ORM\Column(name="name", type="string", length=512, nullable=true)
     */
    private $name;

    /**
     * Relative file path (with subdirectory and filename)
     * @var string|null
     *
     * @ORM\Column(name="file_path", type="string", length=1024, nullable=true)
     */
    private $filePath;

    /**
     * @var Message
     *
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="mediaFiles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="message_id", referencedColumnName="id",onDelete="cascade")
     * })
     */
    private $message;


    /**
     * @var Report
     *
     * @ORM\ManyToOne(targetEntity="Report", inversedBy="mediaFiles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="report_id", referencedColumnName="id",onDelete="cascade")
     * })
     */
    private $report;


    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var DataPoint
     *
     * @ORM\ManyToOne(targetEntity="DataPoint", inversedBy="uploads")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="data_point_id", referencedColumnName="id",onDelete="cascade")
     * })
     */
    private $dataPoint;


    /**
     * @var Task
     *
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="uploads")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task_id", referencedColumnName="id",onDelete="cascade")
     * })
     */
    private $task;

    /**
     * @var MediaType
     *
     * @ORM\ManyToOne(targetEntity="MediaType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="media_type", referencedColumnName="id")
     * })
     */
    private $mediaType;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->updateDate = new \DateTime();
    }


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

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updateDate = new \DateTime();
    }


    public function getUpdateDate(): ?string
    {
        return $this->updateDate ? $this->updateDate->format('Y-m-d H:i:s.u') : null;
    }

    public function setUpdateDate(?DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }


    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }


    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getDataPoint(): ?DataPoint
    {
        return $this->dataPoint;
    }

    public function setDataPoint(?DataPoint $dataPoint): self
    {
        $this->dataPoint = $dataPoint;

        return $this;
    }

    public function getMediaType(): ?MediaType
    {
        return $this->mediaType;
    }

    public function setMediaType(?MediaType $mediaType): self
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    public function getCreationDate(): ?string
    {
        return $this->creationDate ? $this->creationDate->format('Y-m-d') : null;

    }


    public function setCreationDate(?DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

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

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(?Report $report): self
    {
        $this->report = $report;

        return $this;
    }


}
