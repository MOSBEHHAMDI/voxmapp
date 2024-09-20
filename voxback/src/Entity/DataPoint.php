<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(paginationEnabled: false,)]
/**
 * DataPoint
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="data_point")
 * @ORM\Entity
 */
class DataPoint
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */

    private Uuid $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */

    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */

    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="lat_long", type="string", length=255, nullable=true)
     */

    private $latLong;

    /**
     * @var string
     *
     * @ORM\Column(name="altitude", type="string", length=255, nullable=true)
     */

    private $altitude;

    /**
     * @var string|null
     *
     * @ORM\Column(name="contact_number", type="string", length=255, nullable=true)
     */

    private $contactNumber;

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
     * @var bool
     *
     * @ORM\Column(name="public", type="boolean",options={"default": false},nullable=true)
     */

    private $public;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     * })
     */

    private $createdBy;

    /**
     * @var \AccessType
     *
     * @ORM\ManyToOne(targetEntity="AccessType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="access_type", referencedColumnName="id", nullable=false)
     * })
     */

    private $accessType;


    /**
     * @var \City
     *
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=false)
     * })
     */

    private $city;

    /**
     * @var \DataPointType
     *
     * @ORM\ManyToOne(targetEntity="DataPointType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="data_point_type", referencedColumnName="id")
     * })
     */

    private $dataPointType;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="dataPoints")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id",onDelete="SET NULL")
     */

    private $project;


    /**
     * @var \SourceType
     *
     * @ORM\ManyToOne(targetEntity="SourceType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="source_type", referencedColumnName="id" , nullable=false)
     * })
     */
    private $sourceType;

     /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SurveyResult", mappedBy="dataPoint",cascade={"persist"})
     *
     */
    private $surveys;

    /**
     * @ORM\OneToMany(targetEntity="Media", mappedBy="dataPoint",orphanRemoval=true)
     */

    private $uploads;

    /**
     * @var \Status
     *
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status", referencedColumnName="id", nullable=false)
     * })
     */
    private $status;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="facilities")
     *
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="Task", inversedBy="dataPoints", cascade={"remove"})
     * @ORM\JoinTable(name="task_data_point",
     *      joinColumns={@ORM\JoinColumn(name="data_point_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")}
     *      )
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity="History", mappedBy="dataPoint",cascade={"remove"})
     */
    private $history;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->updateDate = new \DateTime();
        $this->uploads = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->history = new ArrayCollection();
        $this->surveys = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setDataPointId(Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getLatLong(): ?string
    {
        return $this->latLong;
    }


    public function setLatLong(?string $latLong): self
    {
        $this->latLong = $latLong;

        return $this;
    }

    public function getAltitude(): ?string
    {
        return $this->altitude;
    }

    public function setAltitude(?string $altitude): self
    {
        $this->altitude = $altitude;

        return $this;
    }

    public function getContactNumber(): ?string
    {
        return $this->contactNumber;
    }

    public function setContactNumber(?string $contactNumber): self
    {
        $this->contactNumber = $contactNumber;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

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
        return $this->updateDate ? $this->updateDate->format('Y-m-d') : null;
    }

    public function setUpdateDate(?DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }


    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(?bool $public): self
    {
        $this->public = $public ?? false;

        return $this;
    }


    public function getAccessType(): ?AccessType
    {
        return $this->accessType;
    }

    public function setAccessType(?AccessType $accessType): self
    {
        $this->accessType = $accessType;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDataPointType(): ?DataPointType
    {
        return $this->dataPointType;
    }

    public function setDataPointType(?DataPointType $dataPointType): self
    {
        $this->dataPointType = $dataPointType;

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

    public function getSourceType(): ?SourceType
    {
        return $this->sourceType;
    }

    public function setSourceType(?SourceType $sourceType): self
    {
        $this->sourceType = $sourceType;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

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
            $upload->setDataPoint($this);
        }

        return $this;
    }

    public function removeUpload(Media $upload): self
    {
        if ($this->uploads->removeElement($upload)) {
            // set the owning side to null (unless already changed)
            if ($upload->getDataPoint() === $this) {
                $upload->setDataPoint(null);
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
            $user->addFacility($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeFacility($this);
        }

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): void
    {
        $this->createdBy = $createdBy;
    }


    /**
     * @return Collection<int, History>
     */
    public function getHistory(): Collection
    {
        return $this->history;
    }

    public function addHistory(History $history): self
    {
        if (!$this->history->contains($history)) {
            $this->history->add($history);
            $history->setDataPoint($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->history->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getDataPoint() === $this) {
                $history->setDataPoint(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        $this->tasks->removeElement($task);

        return $this;
    }

    /**
     * @return Collection<int, SurveyResult>
     */
    public function getSurveys(): Collection
    {
        return $this->surveys;
    }

    public function addSurvey(SurveyResult $survey): self
    {
        if (!$this->surveys->contains($survey)) {
            $this->surveys->add($survey);
            $survey->setDataPoint($this);
        }

        return $this;
    }

    public function removeSurvey(SurveyResult $survey): self
    {
        if ($this->surveys->removeElement($survey)) {
            // set the owning side to null (unless already changed)
            if ($survey->getDataPoint() === $this) {
                $survey->setDataPoint(null);
            }
        }

        return $this;
    }

}
