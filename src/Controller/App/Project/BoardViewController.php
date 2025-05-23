<?php

namespace App\Controller\App\Project;

use App\Controller\Common\GetControllerTrait;
use App\Entity\Project;
use App\Message\Query\App\Project\GetKanbanIssueByBoardId;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route(
    path: '/project/{idProject}/board/{idBoard}/view',
)]
class BoardViewController extends AbstractController
{
    use GetControllerTrait;

    #[Route(
        path: '/',
        name: RouteCollection::PROJECT_BOARD_VIEW->value,
        methods: [Request::METHOD_GET],
    )]
    public function view(
        #[MapEntity(mapping: [
            'idProject' => 'id',
        ])]
        Project $project,
        string $idBoard,
    ): Response {
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
        name: RouteCollection::PROJECT_BOARD_VIEW_STREAM->value,
        methods: [Request::METHOD_GET],
    )]
    public function viewStream(
        #[MapEntity(mapping: [
            'idProject' => 'id',
        ])]
        Project $project,
        string $idBoard,
        Request $request,
    ): Response {
        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
        $kanbanIssuesFormatted = $this->handle(
            new GetKanbanIssueByBoardId($project, $idBoard),
        );

        return $this->render(
            view: 'app/project/board_view.stream.html.twig',
            parameters: [
                'entity' => $project,
                'boardId' => $idBoard,
                'kanbanIssues' => $kanbanIssuesFormatted,
            ],
        );
    }
}
