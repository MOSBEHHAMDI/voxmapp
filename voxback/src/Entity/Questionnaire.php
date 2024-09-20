<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    denormalizationContext: ['groups' => ['Questionnaire:create']],
    paginationEnabled: false)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'project' => 'exact', 'questionnaireType' => 'exact', 'name' => 'ipartial', 'project.name' => 'ipartial'])]
#[ApiFilter(DateFilter::class, properties: ['creationDate', 'updateDate'])]
#[ApiFilter(BooleanFilter::class, properties: ['poll'])]
/**
 * Questionnaire
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="questionnaire", uniqueConstraints={@ORM\UniqueConstraint(name="questionnaire_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_questionnaire_project1_idx", columns={"project"}), @ORM\Index(name="fk_questionnaire_questionnaire_type1_idx", columns={"questionnaire_type"})})
 * @ORM\Entity
 */
class Questionnaire
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    #[Groups(['Questionnaire:create'])]
    private Uuid $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="token", type="string",unique=true,length=255, nullable=false)
     */
    #[Groups(['Questionnaire:create'])]
    private $token;

    /**
     * @var Translation
     *
     * @ORM\ManyToOne(targetEntity="Translation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="name", referencedColumnName="id")
     * })
     */
    #[Groups(['Questionnaire:create'])]
    private $name;

    /**
     * @var Translation
     *
     * @ORM\ManyToOne(targetEntity="Translation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="label", referencedColumnName="id")
     * })
     */
    #[Groups(['Questionnaire:create'])]
    private $welcomeText;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=true)
     */
    #[Groups(['Questionnaire:create'])]
    private $creationDate;

    /**
     * @ORM\Column(type="datetime",name="update_date",nullable=true,)
     */
    #[Groups(['Questionnaire:create'])]
    protected $updateDate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="poll", type="boolean", nullable=true)
     */
    #[Groups(['Questionnaire:create'])]
    private $poll;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="createDataPoint", type="boolean", nullable=true)
     */
    #[Groups(['Questionnaire:create'])]
    private $createDataPoint;


    /**
     * @var QuestionnaireType
     *
     * @ORM\ManyToOne(targetEntity="QuestionnaireType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="questionnaire_type", referencedColumnName="id")
     * })
     */
    #[Groups(['Questionnaire:create'])]
    private $questionnaireType;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Project",inversedBy="questionnaires")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project", referencedColumnName="id",nullable=true)
     * })
     */
    #[Assert\NotBlank]
    #[Groups(['Questionnaire:create'])]
    private $project;

     /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     *
     */
     #[Groups(['Questionnaire:create'])]
    private $emailConfiguration;

    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Section",mappedBy = "questionnaire",cascade={"persist"})
     *
     */
    #[Groups(['Questionnaire:create'])]
    private $sections;

    /**
     * @var bool|null
     *
     * @ORM\Column(type="boolean", options={"default": false}, nullable=true)
     *
     */
    #[Groups(['Questionnaire:create'])]
    private $requestAuthorization;

    /**
     * @var bool|null
     *
     * @ORM\Column(type="boolean", options={"default": false}, nullable=true)
     *
     */
    #[Groups(['Questionnaire:create'])]
    private $comment;

    /**
     * @var bool|null
     *
     * @ORM\Column(type="boolean", options={"default": false}, nullable=true)
     *
     */
    #[Groups(['Questionnaire:create'])]
    private $enableGalleryPhoto;

      /**
     * @var  Collection
     *
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="admins_questionnaires")
     */
      #[Groups(['Questionnaire:create'])]
    private $admins;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="questionnaires")
     */
    #[Groups(['Questionnaire:create'])]
    private $users;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Language", mappedBy="questionnaires")
     */
    #[Groups(['Questionnaire:create'])]
    private $languages;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Metadata", mappedBy="questionnaires")
     */
    #[Groups(['Questionnaire:create'])]
    private $metadatas;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sections = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->creationDate = new \DateTime();
        $this->updateDate = new \DateTime();
        $this->metadatas = new ArrayCollection();
        $this->admins = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getCreateDataPoint(): ?bool
    {
        return $this->createDataPoint;
    }

    public function setCreateDataPoint(?bool $createDataPoint): void
    {
        $this->createDataPoint = $createDataPoint;
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


    public function getUpdateDate(): ?string
    {
        return $this->updateDate ? $this->updateDate->format('Y-m-d') : null;
    }


    public function isPoll(): ?bool
    {
        return $this->poll;
    }

    public function setPoll(?bool $poll): self
    {
        $this->poll = $poll;

        return $this;
    }

    public function getQuestionnaireType(): ?QuestionnaireType
    {
        return $this->questionnaireType;
    }

    public function setQuestionnaireType(?QuestionnaireType $questionnaireType): self
    {
        $this->questionnaireType = $questionnaireType;

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


    public function getSections(): ?Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections->add($section);
            $section->setQuestionnaire($this);
        }
        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->removeElement($section)) {
            $section->setQuestionnaire(null);
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
            $user->addQuestionnaire($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeQuestionnaire($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Language>
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->languages->contains($language)) {
            $this->languages->add($language);
            $language->addQuestionnaire($this);
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        if ($this->languages->removeElement($language)) {
            $language->removeQuestionnaire($this);
        }

        return $this;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updateDate = new \DateTime();
    }

    public function setUpdateDate(?DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * @return Collection<int, Metadata>
     */
    public function getMetadatas(): Collection
    {
        return $this->metadatas;
    }

    public function addMetadata(Metadata $metadata): self
    {
        if (!$this->metadatas->contains($metadata)) {
            $this->metadatas->add($metadata);
            $metadata->addQuestionnaire($this);
        }

        return $this;
    }

    public function removeMetadata(Metadata $metadata): self
    {
        if ($this->metadatas->removeElement($metadata)) {
            $metadata->removeQuestionnaire($this);
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getEmailConfiguration(): ?string
    {
        return $this->emailConfiguration;
    }

    public function setEmailConfiguration(?string $emailConfiguration): self
    {
        $this->emailConfiguration = $emailConfiguration;

        return $this;
    }

    public function isRequestAuthorization(): ?bool
    {
        return $this->requestAuthorization;
    }

    public function setRequestAuthorization(?bool $requestAuthorization): self
    {
        $this->requestAuthorization = $requestAuthorization;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAdmins(): Collection
    {
        return $this->admins;
    }

    public function addAdmin(User $admin): self
    {
        if (!$this->admins->contains($admin)) {
            $this->admins->add($admin);
        }

        return $this;
    }

    public function removeAdmin(User $admin): self
    {
        $this->admins->removeElement($admin);

        return $this;
    }

    public function getComment(): ?bool
    {
        return $this->comment;
    }

    public function setComment(?bool $comment): void
    {
        $this->comment = $comment;
    }

    public function getEnableGalleryPhoto(): ?bool
    {
        return $this->enableGalleryPhoto;
    }

    public function setEnableGalleryPhoto(?bool $enableGalleryPhoto): void
    {
        $this->enableGalleryPhoto = $enableGalleryPhoto;
    }

    public function isComment(): ?bool
    {
        return $this->comment;
    }

    public function isEnableGalleryPhoto(): ?bool
    {
        return $this->enableGalleryPhoto;
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

    public function getWelcomeText(): ?Translation
    {
        return $this->welcomeText;
    }

    public function setWelcomeText(?Translation $welcomeText): static
    {
        $this->welcomeText = $welcomeText;

        return $this;
    }

}
