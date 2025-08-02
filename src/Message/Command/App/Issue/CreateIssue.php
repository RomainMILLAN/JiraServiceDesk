<?php

namespace App\Message\Command\App\Issue;

use App\Entity\Project;
use App\Entity\User;

class CreateIssue extends AbstractIssueDTO
{
    public function __construct(
        Project $project,
        public User $creator,
        public array $attachments = [],
        public ?string $description = null,
        public ?string $assignee = 'null',
    ) {
        parent::__construct(
            summary: '',
            project: $project,
            assignee: $assignee,
        );
    }
}
