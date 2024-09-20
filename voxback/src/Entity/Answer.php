<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
#[ApiResource(
    paginationEnabled: false,
)]

/**
 * Response
 *
 * @ORM\Table(name="answer")
 * @ORM\Entity
 */
class Answer
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
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    #[Groups(['SurveyResult:create'])]
    private $value;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @var SurveyResult
     *
     * @ORM\ManyToOne(targetEntity="SurveyResult", inversedBy="answers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="survey_result", referencedColumnName="id")
     * })
     */
    private $surveyResult;

    /**
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="question", referencedColumnName="id")
     * })
     */
    #[Groups(['SurveyResult:create'])]
    private $question;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

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

    public function getSurveyResult(): ?SurveyResult
    {
        return $this->surveyResult;
    }

    public function setSurveyResult(?SurveyResult $surveyResult): self
    {
        $this->surveyResult = $surveyResult;

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


}
