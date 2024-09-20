<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(paginationEnabled: false,)]
/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity
 */
class Task
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
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     */
    private $endDate;


    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="end_repetition", type="datetime", nullable=true)
     */
    private $endRepetition;


    /**
     * @var int|null
     *
     * @ORM\Column(name="progress", type="integer", nullable=true)
     */
    private $progress;


    /**
     * @var Translation
     *
     * @ORM\ManyToOne(targetEntity="Translation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="name", referencedColumnName="id")
     * })
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="repetition", type="string", length=255, nullable=true)
     */
    private $repetition;


    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="state", type="string", nullable=true)
     */
    private $state;


    /**
     * @var string|null
     *
     * @ORM\Column(name="frequence", type="string", length=255, nullable=true)
     */
    private $frequence;

    /**
     * @var TaskType
     *
     * @ORM\ManyToOne(targetEntity="TaskType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task_type", referencedColumnName="id",nullable=false)
     * })
     */
    private $taskType;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="DataPoint", mappedBy="tasks")
     */
    private $dataPoints;

    /**
     * @ORM\OneToMany(targetEntity="Media", mappedBy="task",orphanRemoval=true)
     */
    private $uploads;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="tasks")
     *
     */
    private $users;


    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Group", mappedBy="tasks")
     *
     */
    private $groups;


    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="app_user", referencedColumnName="id")
     * })
     */
    private $createdBy;

    /**
     * @var ArrayCollection
     *
     * @ORM\Column(type="simple_array",name="tags",nullable=true)
     */
    private $tags;


    /**
     * @var float
     *
     * @ORM\Column(name="status", type="float", nullable=false)
     */
    private $status;

       /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="tasks")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project", referencedColumnName="id",onDelete="SET NULL")
     * })
     */
    private $project;

    public function __construct()
    {
        $this->uploads = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->dataPoints = new ArrayCollection();

    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getProgress(): ?int
    {
        return $this->progress;
    }

    public function setProgress(?int $progress): self
    {
        $this->progress = $progress;

        return $this;
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

    public function getFrequence(): ?string
    {
        return $this->frequence;
    }

    public function setFrequence(?string $frequence): self
    {
        $this->frequence = $frequence;

        return $this;
    }

    public function getTaskType(): ?TaskType
    {
        return $this->taskType;
    }

    public function setTaskType(?TaskType $taskType): self
    {
        $this->taskType = $taskType;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getUploads(): Collection
    {
        return $this->uploads;
    }

    public function addUpload(Media $upload): self
    {
        if (!$this->uploads->contains($upload)) {
            $this->uploads->add($upload);
            $upload->setTask($this);
        }

        return $this;
    }

    public function removeUpload(Media $upload): self
    {
        if ($this->uploads->removeElement($upload)) {
            // set the owning side to null (unless already changed)
            if ($upload->getTask() === $this) {
                $upload->setTask(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addTask($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeTask($this);
        }

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?USER $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->addTask($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeTask($this);
        }

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getStatus(): ?float
    {
        return $this->status;
    }

    public function setStatus(float $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getEndRepetition(): ?\DateTimeInterface
    {
        return $this->endRepetition;
    }

    public function setEndRepetition(?\DateTimeInterface $endRepetition): self
    {
        $this->endRepetition = $endRepetition;

        return $this;
    }

    public function getRepetition(): ?string
    {
        return $this->repetition;
    }

    public function setRepetition(?string $repetition): self
    {
        $this->repetition = $repetition;

        return $this;
    }

    /**
     * @return Collection<int, DataPoint>
     */
    public function getDataPoints(): Collection
    {
        return $this->dataPoints;
    }

    public function addDataPoint(DataPoint $dataPoint): self
    {
        if (!$this->dataPoints->contains($dataPoint)) {
            $this->dataPoints->add($dataPoint);
            $dataPoint->addTask($this);
        }

        return $this;
    }

    public function removeDataPoint(DataPoint $dataPoint): self
    {
        if ($this->dataPoints->removeElement($dataPoint)) {
            $dataPoint->removeTask($this);
        }

        return $this;
    }


    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }


}
