<?php

namespace App\Entity;

use App\Enum\LogEntryAction;
use App\Repository\LogEntryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogEntryRepository::class)]
#[ORM\Table(name: '_log_entry')]
class LogEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: false, enumType: LogEntryAction::class)]
    public ?LogEntryAction $action = null;

    #[ORM\Column(length: 255)]
    public ?string $type = null;

    #[ORM\Column(length: 255)]
    public ?string $objectId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    public ?User $user = null;

    #[ORM\Column(nullable: true)]
    public ?array $context = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct(
        LogEntryAction $action,
        string $type,
        string $objectId,
        User $user,
        ?array $context = [],
    ) {
        $this->action = $action;
        $this->type = $type;
        $this->objectId = $objectId;
        $this->user = $user;
        $this->context = $context;
    }
}
