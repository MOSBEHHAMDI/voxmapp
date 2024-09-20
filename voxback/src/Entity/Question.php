<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(denormalizationContext: ['groups' => ['Question:create']], paginationEnabled: false,)]
#[ApiFilter(OrderFilter::class, properties: ['orderBy' => 'ASC'])]
/**
 * Question
 *
 * @ORM\Table(name="question", uniqueConstraints={@ORM\UniqueConstraint(name="question_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_question_section1_idx", columns={"section"}), @ORM\Index(name="fk_question_question_type1_idx", columns={"questionType"})})
 * @ORM\Entity
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    private Uuid $id;


    /**
     * @var string|null
     *
     * @ORM\Column(name="code", type="string",unique=true,length=255, nullable=false)
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    private $code;


    /**
     * @var Translation
     *
     * @ORM\ManyToOne(targetEntity="Translation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="label", referencedColumnName="id")
     * })
     */
    #[Groups(['Section:create', 'Questionnaire:create', 'Question:create'])]
    private $label;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    private $enabled;
    /**
     * @var bool|null
     *
     * @ORM\Column(name="mandatory", type="boolean", nullable=true)
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    private $mandatory;
    /**
     * @var bool|null
     *
     * @ORM\Column(name="state", type="boolean", nullable=true)
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    private $state;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="last_question", type="boolean", nullable=true, options={"default": false})
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    private $lastQuestion;

    /**
     * @var array|null
     *
     * @ORM\Column(name="config", type="json", options={"jsonb": true}, nullable=true)
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    private $config;

    /**
     * @var array|null
     *
     *
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    private $entries;

    /**
     * @var int|null
     *
     * @ORM\Column(name="order_by", type="integer", nullable=true)
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    private $orderBy;

    /**
     * @var Section
     *
     * @ORM\ManyToOne(targetEntity="Section", inversedBy="questions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="section", referencedColumnName="id", onDelete="cascade")
     * })
     */
    private $section;

    /**
     * @var QuestionType
     *
     * @ORM\ManyToOne(targetEntity="QuestionType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="questionType", referencedColumnName="id", onDelete="cascade")
     * })
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    private $questiontype;

    /**
     * @var VisualisationType
     *
     * @ORM\ManyToOne(targetEntity="VisualisationType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="visualisationType", referencedColumnName="id", onDelete="cascade")
     * })
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    private $visualisationType;

    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Choice",mappedBy = "question",cascade={"persist"})
     *
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    private $choices;

    /**
     * @var Question
     *
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Question")
     * @Doctrine\ORM\Mapping\JoinColumns({
     *   @Doctrine\ORM\Mapping\JoinColumn(name="target_question", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * })
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    #[ApiProperty(readableLink: false, writableLink: false)]
    private $targetQuestion;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->choices = new ArrayCollection();
        $this->enabled = true;

    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(?bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function isMandatory(): ?bool
    {
        return $this->mandatory;
    }

    public function setMandatory(?bool $mandatory): self
    {
        $this->mandatory = $mandatory;

        return $this;
    }

    public function isState(): ?bool
    {
        return $this->state;
    }

    public function setState(?bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function isLastQuestion(): ?bool
    {
        return $this->lastQuestion;
    }

    public function setLastQuestion(?bool $lastQuestion): self
    {
        $this->lastQuestion = $lastQuestion;

        return $this;
    }

    public function getOrderBy(): ?int
    {
        return $this->orderBy;
    }

    public function setOrderBy(?int $orderBy): self
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getQuestiontype(): ?QuestionType
    {
        return $this->questiontype;
    }

    public function setQuestiontype(?QuestionType $questiontype): self
    {
        $this->questiontype = $questiontype;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @return Collection<int, Choice>
     */
    public function getChoices(): Collection
    {
        return $this->choices;
    }

    public function addChoice(Choice $choice): self
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
            $choice->setQuestion($this);
        }

        return $this;
    }

    public function removeChoice(Choice $choice): self
    {
        if ($this->choices->removeElement($choice)) {
            // set the owning side to null (unless already changed)
            if ($choice->getQuestion() === $this) {
                $choice->setQuestion(null);
            }
        }

        return $this;
    }

    public function getConfig(): ?array
    {
        return $this->config;
    }

    public function setConfig(?array $config): self
    {
        $this->config = $config;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getTargetQuestion(): ?Question
    {
        return $this->targetQuestion;
    }

    public function setTargetQuestion(?Question $targetQuestion): void
    {
        $this->targetQuestion = $targetQuestion;
    }

    public function getEntries(): ?array
    {
        return $this->entries;
    }

    public function setEntries(?array $entries): void
    {
        $this->entries = $entries;
    }

    public function getVisualisationType(): ?VisualisationType
    {
        return $this->visualisationType;
    }

    public function setVisualisationType(?VisualisationType $visualisationType): self
    {
        $this->visualisationType = $visualisationType;

        return $this;
    }

    public function getLabel(): ?Translation
    {
        return $this->label;
    }

    public function setLabel(?Translation $label): static
    {
        $this->label = $label;

        return $this;
    }

}
