<?php

namespace App\Message\Command\Admin\Project\Handler;

use App\Entity\Project;
use App\Message\Command\Admin\Project\EditProject;
use App\Message\Command\Admin\Project\GenerateProjectIssueTypes;
use App\Repository\Jira\ProjectRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
readonly class EditProjectHandler
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(EditProject $command): ?Project
    {
        $project = $command->project;
        $jiraProject = $this->projectRepository->get($command->jiraKey);

        if ($jiraProject === null) {
            return null;
        }

        $project->name = $jiraProject->name;
        $project->jiraId = $jiraProject->id;
        $project->assignableRolesIds = $command->assignableRolesIds;
        $project->backlogStatusesIds = $command->backlogStatusesIds;
        $project->defaultIssueType = $command->defaultIssueType;

        // Set default assignee.
        if ($jiraProject->assigneeType === 'PROJECT_LEAD') {
            $project->defaultAssigneeAccountId = $jiraProject->lead['accountId'];
        }

        foreach ($project->getUsers() as $projectUser) {
            $project->removeUser($projectUser);
        }
        foreach ($command->users as $user) {
            $project->addUser($user);
        }

        $this->commandBus->dispatch(
            new GenerateProjectIssueTypes(
                project: $project,
            ),
        );

        return $project;
    }
}
