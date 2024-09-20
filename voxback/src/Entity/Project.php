<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    paginationEnabled: false
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'id' => 'exact',
        'projectType' => 'exact',
        'name' => 'ipartial'
    ]
)]
/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    private Uuid $id;

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
     * @var Translation
     *
     * @ORM\ManyToOne(targetEntity="Translation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="description", referencedColumnName="id",nullable=true)
     * })
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="project_pic", type="string", length=255, nullable=true)
     */
    private $projectPic;

    /**
     * @var string|null
     *
     * @ORM\Column(name="background_pic", type="string", length=255, nullable=true)
     */
    private $backgroundPic;

    /**
     * @var int|null
     *
     * @ORM\Column(name="color_pallet", type="integer", nullable=true)
     */
    private $colorPallet;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client", referencedColumnName="id")
     * })
     */
    private $client;

    /**
     * @var ProjectType
     *
     * @ORM\ManyToOne(targetEntity="ProjectType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_type", referencedColumnName="id")
     * })
     */
    private $projectType;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Language", inversedBy="project")
     * @ORM\JoinTable(name="project_has_language",
     *   joinColumns={
     *     @ORM\JoinColumn(name="project", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="language", referencedColumnName="id")
     *   }
     * )
     */
    private $language = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="projects")
     */
    private $users = array();

    /**
     * @ORM\OneToMany(targetEntity="Questionnaire", mappedBy="project", orphanRemoval=true)
     */
    private $questionnaires;


    /**
     * @ORM\OneToMany(targetEntity="DataPoint", mappedBy="project")
     */
    private $dataPoints;


    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="project")
     */
    private $tasks;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->language = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->questionnaires = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dataPoints = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDescription(): ?Translation
    {
        return $this->description;
    }

    public function setDescription(Translation $description): void
    {
        $this->description = $description;
    }



    public function getProjectPic(): ?string
    {
        return $this->projectPic;
    }

    public function setProjectPic(?string $projectPic): self
    {
        $this->projectPic = $projectPic;

        return $this;
    }

    public function getBackgroundPic(): ?string
    {
        return $this->backgroundPic;
    }

    public function setBackgroundPic(?string $backgroundPic): self
    {
        $this->backgroundPic = $backgroundPic;

        return $this;
    }

    public function getColorPallet(): ?int
    {
        return $this->colorPallet;
    }

    public function setColorPallet(?int $colorPallet): self
    {
        $this->colorPallet = $colorPallet;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getProjectType(): ?ProjectType
    {
        return $this->projectType;
    }

    public function setProjectType(?ProjectType $projectType): self
    {
        $this->projectType = $projectType;

        return $this;
    }

    /**
     * @return Collection<int, Language>
     */
    public function getLanguage(): Collection
    {
        return $this->language;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->language->contains($language)) {
            $this->language->add($language);
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        $this->language->removeElement($language);

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
            $user->addProject($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeProject($this);
        }

        return $this;
    }

    /* public function getQuestionnaires(): Collection
     {
         return $this->questionnaires;
     }*/

    public function addQuestionnaire(Questionnaire $questionnaire): self
    {
        if (!$this->questionnaires->contains($questionnaire)) {
            $this->questionnaires->add($questionnaire);
            $questionnaire->setProject($this);
        }

        return $this;
    }

    public function removeQuestionnaire(Questionnaire $questionnaire): self
    {
        if ($this->questionnaires->removeElement($questionnaire)) {
            $questionnaire->setProject(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Questionnaire>
     */
    public function getQuestionnaires(): Collection
    {
        return $this->questionnaires;
    }


    /**
     * @return Collection<int, dataPoint>
     */
    public function getDataPoints(): Collection
    {
        return $this->dataPoints;
    }

    public function addDataPoint(dataPoint $dataPoint): self
    {
        if (!$this->dataPoints->contains($dataPoint)) {
            $this->dataPoints->add($dataPoint);
            $dataPoint->setProject($this);
        }

        return $this;
    }

    public function removeDataPoint(dataPoint $dataPoint): self
    {
        if ($this->dataPoints->removeElement($dataPoint)) {
            // set the owning side to null (unless already changed)
            if ($dataPoint->getProject() === $this) {
                $dataPoint->setProject(null);
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
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }

    public function getName(): ?Translation
    {
        return $this->name;
    }

    public function setName(?Translation $name): static
    {
        $this->name = $name;

        return $this;
    }




}
