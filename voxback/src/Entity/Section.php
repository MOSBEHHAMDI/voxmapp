<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;


#[ApiFilter(OrderFilter::class, properties: ['orderBy' => 'ASC'])]
#[ApiResource(
    paginationEnabled: false,
     denormalizationContext: [
        'groups' => ['Section:create'],
    ],
)]
/**
 * Section
 *
 * @ORM\Table(name="section", uniqueConstraints={@ORM\UniqueConstraint(name="section_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_section_questionnaire1_idx", columns={"questionnaire"})})
 * @ORM\Entity
 */
class Section
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    #[Groups(['Questionnaire:create','Section:create'])]
    private Uuid $id;

    /**
     * @var Translation
     *
     * @ORM\ManyToOne(targetEntity="Translation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="name", referencedColumnName="id")
     * })
     */
    #[Groups(['Questionnaire:create','Section:create'])]
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="order_by", type="integer", nullable=true)
     */
    #[Groups(['Questionnaire:create','Section:create'])]
    private $orderBy;

    /**
     * @var Questionnaire
     *
     * @ORM\ManyToOne(targetEntity="Questionnaire", inversedBy="sections")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="questionnaire", referencedColumnName="id", onDelete="cascade")
     * })
     */
    private $questionnaire;


    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Question",mappedBy = "section",cascade={"persist"})
     *
     */
    #[Groups(['Questionnaire:create','Section:create'])]
    private $questions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getQuestionnaire(): ?Questionnaire
    {
        return $this->questionnaire;
    }

    public function setQuestionnaire(?Questionnaire $questionnaire): self
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }

    public function getQuestions(): ?Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setSection($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            $question->setSection(null);
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
