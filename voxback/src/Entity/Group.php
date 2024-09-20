<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    paginationEnabled: false
)]
/**
 * Group
 *
 * @ORM\Table(name="app_group", uniqueConstraints={@ORM\UniqueConstraint(name="group_id_UNIQUE", columns={"id"})})
 * @ORM\Entity
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    #[ApiProperty(identifier: true)]
    private Uuid $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="label", type="string", length=100, nullable=true)
     */
    private $label;

    /**
     * @var array|null
     *
     * @ORM\Column(name="roles", type="json", nullable=true)
     */
    private $roles;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     *
     */

    private $users;


    /**
     * @var  ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Task", inversedBy="groups")
     */
    private $tasks;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->groups = new ArrayCollection();

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

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeGroup($this);
        }

        return $this;
    }

    public static function getAllRoles()
    {
        /**
         * 'Non Registered User' => 'ROLE_NON_REGISTERED_USER',
         * 'Registered User with free account' => 'ROLE_FREE_ACCESS',
         * 'Registered User With payed account' getAllRoles=> 'ROLE_PAYED_ACCESS',
         * 'Client with users' => 'ROLE_CLIENT'
         **/
        return ['Super Admin' => 'ROLE_SUPER_ADMIN',
            'Admin Access' => 'ROLE_ADMIN_ACCESS',
            'Use of baseline' => 'ROLE_BASELINE',
            'Use of dashboard' => 'ROLE_DASHBOARD',
            'Use of task management' => 'ROLE_TASK_MANAGEMENT',
            'Create Questionnaire' => 'ROLE_QUESTIONNAIRE',
            'Create Users' => 'ROLE_CREATE_USERS',
            'Check public data point' => 'ROLE_CHECK_PUBLIC_DATA_POINT',
            'Create public data point' => 'ROLE_CREATE_PUBLIC_DATA_POINT',
            'Manage data point' => 'ROLE_MANAGE_PUBLIC_DATA_POINT',
            'Network Collection' => 'ROLE_NETWORK_MANAGEMENT',
            'Network Chat' => 'ROLE_NETWORK_CHAT',
            'Questionnaire Management' => 'ROLE_QUESTIONNAIRE_MANAGEMENT',
            'Users Management' => 'ROLE_USER_MANAGEMENT',
            'Project Management' => 'ROLE_PROJECT_MANAGEMENT',
            'Polls Management' => 'ROLE_POLLS_MANAGEMENT',
            'Data Point Manager' => 'ROLE_DATA_POINT_MANAGER',
        ];
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

}
