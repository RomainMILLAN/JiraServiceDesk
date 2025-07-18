<?php

namespace App\Controller\App\Project\Board;

use App\Controller\App\Project\AbstractController;
use App\Controller\Common\GetControllerTrait;
use App\Entity\Project;
use App\Entity\User;
use App\Message\Query\App\Project\GetKanbanIssueByBoardId;
use App\Repository\Jira\UserRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Turbo\TurboBundle;

#[Route(
    path: '/project/{key}/board/{idBoard}/view',
)]
#[IsGranted('ROLE_APP_VIEW_KANBAN')]
class ViewController extends AbstractController
{
    use GetControllerTrait;

    public function __construct(
        private readonly UserRepository $userRepository,
        #[Autowire(env: 'JIRA_ACCOUNT_ID')]
        private readonly string $jiraAPIAccountId,
    ) {
    }

    #[Route(
        path: '/',
        name: RouteCollection::VIEW->value,
        methods: [Request::METHOD_GET],
    )]
    public function view(
        #[MapEntity(mapping: [
            'key' => 'jiraKey',
        ])]
        Project $project,
        string $idBoard,
    ): Response {
        $this->setCurrentProject($project);

        return $this->render(
            view: 'app/project/board_view.html.twig',
            parameters: [
                'entity' => $project,
                'boardId' => $idBoard,
            ],
        );
    }

    #[Route(
        path: '/stream',
        name: RouteCollection::VIEW_STREAM->value,
        methods: [Request::METHOD_GET],
    )]
    public function viewStream(
        #[MapEntity(mapping: [
            'key' => 'jiraKey',
        ])]
        Project $project,
        string $idBoard,
        #[CurrentUser]
        User $user,
        Request $request,
    ): Response {
        $this->setCurrentProject($project);
        $assignees = [];
        $assigneesIds = $this->userRepository->getAssignableUser($project);
        foreach ($assigneesIds as $assigneesId) {
            $assignees[$assigneesId->accountId] = $this->userRepository->getUserById($assigneesId->accountId);
        }
        $assignees[$this->jiraAPIAccountId] = [
            'displayName' => sprintf('%s (Support)', $user->getFullName()),
            'accountId' =>  $this->jiraAPIAccountId,
            'avatarUrls' => null,
        ];
        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
        $kanbanIssuesFormatted = $this->handle(
            new GetKanbanIssueByBoardId($project, $idBoard, $request->get('assignee', '')),
        );

        return $this->render(
            view: 'app/project/board_view.stream.html.twig',
            parameters: [
                'entity' => $project,
                'boardId' => $idBoard,
                'kanbanIssues' => $kanbanIssuesFormatted,
                'assignees' => $assignees,
            ],
        );
    }
}
