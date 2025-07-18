<?php

namespace App\Message\Query\App\Project;

use App\Entity\Project;

class GetKanbanIssueByBoardId
{
    public function __construct(
        public Project $project,
        public string $boardId,
        public ?string $assignee = '',
    ) {
    }
}
