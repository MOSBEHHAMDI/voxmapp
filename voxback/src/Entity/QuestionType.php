<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;


#[ApiResource(
    paginationEnabled: false,
)]
/**
 * QuestionType
 *
 * @ORM\Table(name="question_type", uniqueConstraints={@ORM\UniqueConstraint(name="question_type_UNIQUE", columns={"id"})})
 * @ORM\Entity
 */
class QuestionType
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
     * @ORM\Column(name="label", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="VisualisationType", mappedBy="questionType")
     */
    private $visualisationType;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->visualisationType = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, VisualisationType>
     */
    public function getVisualisationType(): ?Collection
    {
        return $this->visualisationType;
    }

    public function addVisualisationType(VisualisationType $visualisationType): self
    {
        if (!$this->visualisationType->contains($visualisationType)) {
            $this->visualisationType->add($visualisationType);
            $visualisationType->addQuestionType($this);
        }

        return $this;
    }

    public function removeVisualisationType(VisualisationType $visualisationType): self
    {
        if ($this->visualisationType->removeElement($visualisationType)) {
            $visualisationType->removeQuestionType($this);
        }

        return $this;
    }

}
