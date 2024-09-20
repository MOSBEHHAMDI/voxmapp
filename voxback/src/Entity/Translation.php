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
 * Translation
 *
 * @ORM\Table(name="translation")
 * @ORM\Entity
 */
class Translation
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
     * @ORM\Column(name="code", type="string", length=45, nullable=true , unique=true)
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;


  /**
     * @var array|null
     *
     * @ORM\Column(name="translation", type="json", options={"jsonb": true}, nullable=true)
     */
     private $translation;



    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language", referencedColumnName="id")
     * })
     */
    private $language;

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getTranslation(): ?array
    {
        return $this->translation;
    }

    public function setTranslation(?array $translation): static
    {
        $this->translation = $translation;

        return $this;
    }




}
