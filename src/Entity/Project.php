<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Gedmo\SoftDeleteable()]
class Project
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    public ?int $jiraId = null;

    #[ORM\Column(length: 255)]
    public ?string $jiraKey = null;

    #[ORM\Column(length: 255)]
    public ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $description = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'projects')]
    private Collection $users;

    /**
     * @var Collection<int, IssueType>
     */
    #[ORM\OneToMany(targetEntity: IssueType::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $issuesTypes;

    #[ORM\Column]
    public array $assignableRolesIds = [];

    #[ORM\Column]
    public array $backlogStatusesIds = [];

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    public ?IssueType $defaultIssueType = null;

    #[ORM\Column(length: 255, nullable: true)]
    public ?string $defaultAssigneeAccountId = null;

    public function __construct(
        string $name,
        int $jiraId,
        string $jiraKey,
        ?string $description = null,
    ) {
        $this->name = $name;
        $this->jiraId = $jiraId;
        $this->jiraKey = $jiraKey;
        $this->description = $description;

        $this->users = new ArrayCollection();
        $this->issuesTypes = new ArrayCollection();
        $this->assignableRolesIds = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (! $this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, IssueType>
     */
    public function getIssuesTypes(): Collection
    {
        return $this->issuesTypes;
    }

    public function addIssuesType(IssueType $issuesType): static
    {
        if (! $this->issuesTypes->contains($issuesType)) {
            $this->issuesTypes->add($issuesType);
            $issuesType->project = $this;
        }

        return $this;
    }

    public function removeIssuesType(IssueType $issuesType): static
    {
        if ($this->issuesTypes->removeElement($issuesType)) {
            if ($issuesType->project === $this) {
                $issuesType->project = null;
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s_%s_%s', $this->id, $this->jiraId, $this->jiraKey);
    }
}
