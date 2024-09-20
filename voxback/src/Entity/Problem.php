<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
#[ApiResource(
        paginationEnabled: false,
)]

/**
 * Problem
 *
 * @ORM\Table(name="problem")
 * @ORM\Entity
 */
class Problem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    private Uuid $id;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="creation_time", type="datetime", nullable=true)
     */
    private $creationTime;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="resolution_time", type="datetime", nullable=true)
     */
    private $resolutionTime;

    /**
     * @var DataPoint
     *
     * @ORM\ManyToOne(targetEntity="DataPoint")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="data_point", referencedColumnName="id")
     * })
     */
    private $dataPoint;

    /**
     * @var ProblmeType
     *
     * @ORM\ManyToOne(targetEntity="ProblemType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="problme_type", referencedColumnName="id")
     * })
     */
    private $problmeType;

    /**
     * @var SurveyResult
     *
     * @ORM\ManyToOne(targetEntity="SurveyResult")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="response_list", referencedColumnName="id")
     * })
     */
    private $surveyResult;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreationTime(): ?\DateTimeInterface
    {
        return $this->creationTime;
    }

    public function setCreationTime(?\DateTimeInterface $creationTime): self
    {
        $this->creationTime = $creationTime;

        return $this;
    }

    public function getResolutionTime(): ?\DateTimeInterface
    {
        return $this->resolutionTime;
    }

    public function setResolutionTime(?\DateTimeInterface $resolutionTime): self
    {
        $this->resolutionTime = $resolutionTime;

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

    public function getProblmeType(): ?ProblemType
    {
        return $this->problmeType;
    }

    public function setProblmeType(?ProblemType $problmeType): self
    {
        $this->problmeType = $problmeType;

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


}
