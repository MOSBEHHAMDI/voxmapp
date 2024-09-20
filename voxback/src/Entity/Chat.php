<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;

#[ApiResource(
    paginationEnabled: false, denormalizationContext: [
        'groups' => ['Chat:create'],
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['orderBy' => 'ASC'])]
/**
 * Chat
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="chat", uniqueConstraints={@ORM\UniqueConstraint(name="chat_id_UNIQUE", columns={"id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ChatRepository")
 */
class Chat
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */

    private Uuid $id;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="text", length=90, nullable=true)
     */
    #[Groups(['Chat:create'])]
    private $label;

    /**
     * @ORM\Column(type="datetime",name="update_date",nullable=true,)
     */
    #[Groups(['Chat:create'])]
    protected $updateDate;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     * })
     */
    #[Groups(['Chat:create'])]
    private $createdBy;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="chats")
     */
    #[Groups(['Chat:create'])]
    private $receivers = array();

    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Message", mappedBy = "chat", cascade={"persist"}, orphanRemoval=true)
     *
     */
    #[Groups(['Chat:create'])]
    private $messages;

    /**
     * @ORM\ManyToOne(targetEntity="Report",inversedBy="chat")
     */
    #[Groups(['Chat:create'])]
    private $report;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="orderBy", type="datetime", nullable=true)
     */
     #[Groups(['Chat:create'])]
     private $orderBy;
  

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->receivers = new ArrayCollection();
        $this->updateDate = new \DateTime();

    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getOrderBy(): ?\DateTimeInterface
    {
        if (!empty($this->messages) && isset($this->messages[0])) {
            $this->orderBy = $this->messages[0]->getSendDate();
        }
        return $this->orderBy;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function getLatestMessage(): ?Message
    {
        if ($this->messages->isEmpty()) {
            return null;
        }
        $latestMessage = $this->messages->first();
        foreach ($this->messages as $message) {
            if ($message->getSendDate() > $latestMessage->getSendDate()) {
                $latestMessage = $message;
            }
        }
        return $latestMessage;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setChat($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getChat() === $this) {
                $message->setChat(null);
            }
        }

        return $this;
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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
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
     * @return Collection<int, User>
     */
    public function getReceivers(): Collection
    {
        return $this->receivers;
    }

    public function addReceiver(User $receiver): self
    {
        if (!$this->receivers->contains($receiver)) {
            $this->receivers->add($receiver);
            $receiver->addChat($this);
        }

        return $this;
    }

    public function removeReceiver(User $receiver): self
    {
        if ($this->receivers->removeElement($receiver)) {
            $receiver->removeChat($this);
        }

        return $this;
    }

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(?Report $report): self
    {
        $this->report = $report;

        return $this;
    }

    public function setOrderBy(?\DateTimeInterface $orderBy): static
    {
        $this->orderBy = $orderBy;

        return $this;
    }
}
