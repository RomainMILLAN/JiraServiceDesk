<?php

namespace App\Message\Command\Admin\Project\Handler;

use App\Entity\Project;
use App\Exception\Project\ProjectAlreadyExistException;
use App\Message\Command\Admin\Project\CreateProject;
use App\Message\Command\Admin\Project\GenerateProjectIssueTypes;
use App\Repository\Jira\ProjectRepository;
use App\Repository\ProjectRepository as AppProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
readonly class CreateProjectHandler
{
    public function __construct(
        private AppProjectRepository $appProjectRepository,
        private ProjectRepository $jiraProjectRepository,
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(CreateProject $command): ?Project
    {
        if ($this->appProjectRepository->findOneBy([
            'jiraKey' => $command->jiraKey,
        ]) !== null) {
            throw new ProjectAlreadyExistException();
        }

        $jiraProject = $this->jiraProjectRepository->get($command->jiraKey);

        if ($jiraProject === null) {
            return null;
        }

        $project = new Project(
            name: $jiraProject->name,
            jiraId: $jiraProject->id,
            jiraKey: $jiraProject->key,
            description: $jiraProject->description,
        );

        // Set default assignee.
        if ($jiraProject->assigneeType === 'PROJECT_LEAD') {
            $project->defaultAssigneeAccountId = $jiraProject->lead['accountId'];
        }

        foreach ($command->users as $user) {
            $project->addUser($user);
        }

        $this->entityManager->persist($project);

        $this->commandBus->dispatch(
            new GenerateProjectIssueTypes(
                project: $project,
                jiraIssueTypes: $jiraProject->issueTypes,
            )
        );

        return $project;
    }
}
