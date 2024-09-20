<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    denormalizationContext: ['groups' => ['SurveyResult:create']],
    paginationEnabled: false)]
/**
 * SurveyResult
 *
 * @ORM\Table(name="survey_result")
 * @ORM\Entity
 */
class SurveyResult
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    #[Groups(['SurveyResult:create'])]
    private Uuid $id;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    #[Groups(['SurveyResult:create'])]
    private $startDate;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="finish_date", type="datetime", nullable=true)
     */
    #[Groups(['SurveyResult:create'])]
    private $finishDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    #[Groups(['SurveyResult:create'])]
    private $comment;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userEnumerator", referencedColumnName="id")
     * })
     */
    #[Groups(['SurveyResult:create'])]
    private $userEnumerator;

    /**
     * @var Questionnaire
     *
     * @ORM\ManyToOne(targetEntity="Questionnaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="questionnaire", referencedColumnName="id")
     * })
     */
    #[Groups(['SurveyResult:create'])]
    private $questionnaire;

    /**
     * @var DataPoint
     *
     * @ORM\ManyToOne(targetEntity="DataPoint")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="data_point", referencedColumnName="id", nullable=true)
     * })
     */
    #[Groups(['SurveyResult:create'])]
    private $dataPoint;

    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="surveyResult",cascade={"persist"})
     *
     */
    #[Groups(['SurveyResult:create'])]
    private $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
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

    public function getFinishDate(): ?\DateTimeInterface
    {
        return $this->finishDate;
    }

    public function setFinishDate(?\DateTimeInterface $finishDate): self
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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

    public function getDataPoint(): ?DataPoint
    {
        return $this->dataPoint;
    }

    public function setDataPoint(?DataPoint $dataPoint): self
    {
        $this->dataPoint = $dataPoint;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setSurveyResult($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getSurveyResult() === $this) {
                $answer->setSurveyResult(null);
            }
        }

        return $this;
    }

    public function getUserEnumerator(): ?User
    {
        return $this->userEnumerator;
    }

    public function setUserEnumerator(?User $userEnumerator): self
    {
        $this->userEnumerator = $userEnumerator;

        return $this;
    }


}
