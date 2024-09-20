<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    paginationEnabled: false,
)]
/**
 * VisualisationType
 *
 * @ORM\Table(name="visualisation_type", uniqueConstraints={@ORM\UniqueConstraint(name="visualisation_type_UNIQUE", columns={"id"})})
 * @ORM\Entity
 */
class VisualisationType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    private Uuid $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type_name", type="string", length=255, nullable=true)
     */
    private $typeName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="QuestionType", inversedBy="visualisationType")
     * @ORM\JoinTable(name="visualisation_type_has_question_type",
     *   joinColumns={
     *     @ORM\JoinColumn(name="visualisation_type_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="question_type_id", referencedColumnName="id")
     *   }
     * )
     */
    private $questionType;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questionType = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }

    public function setTypeName(?string $typeName): self
    {
        $this->typeName = $typeName;

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

    /**
     * @return Collection<int, QuestionType>
     */
    public function getQuestionType(): Collection
    {
        return $this->questionType;
    }

    public function addQuestionType(QuestionType $questionType): self
    {
        if (!$this->questionType->contains($questionType)) {
            $this->questionType->add($questionType);
        }

        return $this;
    }

    public function removeQuestionType(QuestionType $questionType): self
    {
        $this->questionType->removeElement($questionType);

        return $this;
    }

}
