<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(paginationEnabled: false)]
#[ApiFilter(OrderFilter::class, properties: ['orderBy' => 'ASC'])]

/**
 * Choice
 *
 * @ORM\Table(name="choice", uniqueConstraints={@ORM\UniqueConstraint(name="choice_id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_choice_question1_idx", columns={"question"})})
 * @ORM\Entity
 */
class Choice
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
     * @var Translation
     *
     * @ORM\ManyToOne(targetEntity="Translation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="label", referencedColumnName="id")
     * })
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    private $label;

    /**
     * @var int|null
     *
     * @ORM\Column(name="order_by", type="integer", nullable=true)
     */
    #[Groups(['Questionnaire:create', 'Section:create', 'Question:create'])]
    private $orderBy;

    /**
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="choices")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="question", referencedColumnName="id" ,onDelete="cascade")
     * })
     */
    private $question;


    /**
     * @var string|null
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    #[Groups(['Questionnaire:create', 'Section:create'])]
    private $code;

     /**
     * @var bool|null
     *
     * @Doctrine\ORM\Mapping\Column(type="boolean", nullable=true)
     *
     */
     #[Groups(['Questionnaire:create', 'Section:create'])]
    private $numberOption;

    /**
     * @var Question
     *
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="Question")
     * @Doctrine\ORM\Mapping\JoinColumns({
     *   @Doctrine\ORM\Mapping\JoinColumn(name="target_question", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * })
	 *
	 *
     */
    #[Groups(['Questionnaire:create', 'Section:create'])]
    private $targetQuestion;

      /**
     * @var Media
     *
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity="Media", cascade={"persist"})
     * @Doctrine\ORM\Mapping\JoinColumns({
     *   @Doctrine\ORM\Mapping\JoinColumn(name="picture", referencedColumnName="id", nullable=true)
     * })
     */
      #[Groups(['Questionnaire:create', 'Section:create'])]
    private $picture;


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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getNumberOption(): ?bool
    {
        return $this->numberOption;
    }

    public function setNumberOption(?bool $number_option): void
    {
        $this->numberOption = $number_option;
    }

    public function getTargetQuestion(): ?Question
    {
        return $this->targetQuestion;
    }

    public function setTargetQuestion(?Question $targetQuestion): void
    {
        $this->targetQuestion = $targetQuestion;
    }

    public function isNumberOption(): ?bool
    {
        return $this->numberOption;
    }

    public function getPicture(): ?Media
    {
        return $this->picture;
    }

    public function setPicture(?Media $picture): self
    {
        $this->picture = $picture;

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
