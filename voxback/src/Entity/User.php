<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\GraphQl\DeleteMutation;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\EventListener\UserMutationResolver;
use App\State\UserPasswordHasher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\Mutation;

#[ApiResource(
    paginationEnabled: false,
    graphQlOperations: [
        new Query(),
        new QueryCollection(),
        new Mutation(resolver: UserMutationResolver::class, name: 'create'),
        new DeleteMutation(name: 'delete'),
        new Mutation(resolver: UserMutationResolver::class, name: 'update')
    ]
)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
/**
 * User
 *
 * @ORM\Table(name="app_user", uniqueConstraints={@ORM\UniqueConstraint(name="user_id_UNIQUE", columns={"id"})},indexes={@ORM\Index(name="fk_user_user_type1_idx", columns={"subscription_type"}), @ORM\Index(name="fk_user_language1_idx", columns={"language"})})
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    #[ApiProperty(identifier: true)]
    private Uuid $id;

    #[Assert\NotBlank]
    #[Assert\Email]
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     *
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="user_name", type="string", length=255, nullable=true)
     */
    private $userName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    private ?string $plainPassword = null;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_number", type="string", length=200, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="available_data_storage", type="decimal", precision=10, scale=0, nullable=true)
     */

    private $availableDataStorage;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */

    private $address;

    /**
     * @var string|null
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */

    private $firstName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="surname", type="string", length=255, nullable=true)
     */

    private $surname;

    /**
     * @var int|null
     *
     * @ORM\Column(name="postal_code", type="string", nullable=true)
     */

    private $postalCode;

    /**
     * @var Media|null
     *
     * @ORM\OneToOne(targetEntity="Media")
     */
    private $profilePic;

    /**
     * @var Media|null
     *
     * @ORM\OneToOne(targetEntity="Media")
     */
    private $backgroundPic;

    /**
     * @var int|null
     *
     * @ORM\Column(name="color_pallete", type="integer", nullable=true)
     */
    private $colorPallete;


    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language", referencedColumnName="id")
     * })
     */
    private $language;


    /**
     * @var SubscriptionType
     *
     * @ORM\ManyToOne(targetEntity="SubscriptionType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subscription_type", referencedColumnName="id")
     * })
     */

    private $subscriptionType;


    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Client", inversedBy="users")
     * @ORM\JoinTable(name="user_has_client",
     *   joinColumns={
     *     @ORM\JoinColumn(name="client_user", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="client", referencedColumnName="id")
     *   }
     * )
     */
    private $clients = array();


    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="DataPoint", inversedBy="users")
     * @ORM\JoinTable(name="user_has_datapoint",
     *   joinColumns={
     *     @ORM\JoinColumn(name="app_user", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="data_point", referencedColumnName="id")
     *   }
     * )
     */

    private $facilities = array();

    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     * @ORM\JoinTable(name="user_has_group",
     *   joinColumns={
     *     @ORM\JoinColumn(name="app_user", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="app_group", referencedColumnName="id")
     *   }
     * )
     */
    private $groups;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     * })
     */
    private $city;


    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Chat", inversedBy="receivers")
     * @ORM\JoinTable(name="user_has_chat",
     *   joinColumns={
     *     @ORM\JoinColumn(name="chat_user", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="chat", referencedColumnName="id")
     *   }
     * )
     */
    private $chats = array();


    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Project", inversedBy="users")
     * @ORM\JoinTable(name="user_has_project",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="project", referencedColumnName="id")
     *   }
     * )
     */
    private $projects = array();

    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Questionnaire", inversedBy="users")
     * @ORM\JoinTable(name="user_has_Questionnaire",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="questionnaire", referencedColumnName="id")
     *   }
     * )
     */
    private $questionnaires = array();

    /**
     * @var  ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Task", inversedBy="users")
     */
    private $tasks;

    /**
     * @var Project|null
     *
     * @ORM\OneToOne(targetEntity="Project")
     */
    private $currentProject;


    /**
     * @ORM\ManyToMany(targetEntity="Message", mappedBy="seenBy")
     */
    private $seenMessages;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->questionnaires = new ArrayCollection();
        $this->facilities = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->seenMessages = new ArrayCollection();


    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return array
     * For serialisation
     */
    public function getRoles(): array
    {
        $roles = [];
        /** @var Group $group */
        foreach ($this->groups as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }
        return array_unique($roles);
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAvailableDataStorage(): ?string
    {
        return $this->availableDataStorage;
    }

    public function setAvailableDataStorage(?string $availableDataStorage): self
    {
        $this->availableDataStorage = $availableDataStorage;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function setProfilePic(?Media $media): self
    {
        $this->profilePic = $media;

        return $this;
    }

    public function getProfilePic(): ?Media
    {
        return $this->profilePic;
    }

    public function getBackgroundPic(): ?Media
    {
        return $this->backgroundPic;
    }

    public function setBackgroundPic(?Media $backgroundPic): self
    {
        $this->backgroundPic = $backgroundPic;
        return $this;
    }

    public function getColorPallete(): ?int
    {
        return $this->colorPallete;
    }

    public function setColorPallete(?int $colorPallete): self
    {
        $this->colorPallete = $colorPallete;

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

    public function getSubscriptionType(): ?SubscriptionType
    {
        return $this->subscriptionType;
    }

    public function setSubscriptionType(?SubscriptionType $subscriptionType): self
    {
        $this->subscriptionType = $subscriptionType;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        $this->clients->removeElement($client);

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        $this->groups->removeElement($group);

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        $this->projects->removeElement($project);

        return $this;
    }

    public function getQuestionnaires(): Collection
    {
        return $this->questionnaires;
    }

    public function addQuestionnaire(Questionnaire $questionnaire): self
    {
        if (!$this->questionnaires->contains($questionnaire)) {
            $this->questionnaires->add($questionnaire);
        }
        return $this;
    }

    public function removeQuestionnaire(Questionnaire $questionnaire): self
    {
        $this->questionnaires->removeElement($questionnaire);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, DataPoint>
     */
    public function getFacilities(): Collection
    {
        return $this->facilities;
    }

    public function addFacility(DataPoint $facility): self
    {
        if (!$this->facilities->contains($facility)) {
            $this->facilities->add($facility);
        }

        return $this;
    }

    public function removeFacility(DataPoint $facility): self
    {
        $this->facilities->removeElement($facility);

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        $this->tasks->removeElement($task);

        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats->add($chat);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        $this->chats->removeElement($chat);

        return $this;
    }

    public function getCurrentProject(): ?Project
    {
        return $this->currentProject;
    }

    public function setCurrentProject(?Project $currentProject): self
    {
        $this->currentProject = $currentProject;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getSeenMessages(): Collection
    {
        return $this->seenMessages;
    }

    public function addSeenMessage(Message $seenMessage): self
    {
        if (!$this->seenMessages->contains($seenMessage)) {
            $this->seenMessages->add($seenMessage);
            $seenMessage->addSeenBy($this);
        }

        return $this;
    }

    public function removeSeenMessage(Message $seenMessage): self
    {
        if ($this->seenMessages->removeElement($seenMessage)) {
            $seenMessage->removeSeenBy($this);
        }

        return $this;
    }




}
