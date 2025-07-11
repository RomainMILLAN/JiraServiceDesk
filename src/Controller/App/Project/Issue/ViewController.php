<?php

namespace App\Controller\App\Project\Issue;

use App\Controller\App\Project\AbstractController;
use App\Controller\Common\GetControllerTrait;
use App\Entity\Project;
use App\Entity\User;
use App\Form\App\Issue\IssueCommentFormType;
use App\Message\Command\App\Issue\CreateComment;
use App\Message\Query\App\Issue\GetFullIssue;
use App\Repository\Contracts\Jira\IssueRepositoryInterface;
use App\Service\IssueHtmlProcessor;
use JiraCloud\JiraException;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\UX\Turbo\TurboBundle;

#[Route(
    path: '/project/{key}/issues',
)]
class ViewController extends AbstractController
{
    use GetControllerTrait;

    public function __construct(
        private IssueRepositoryInterface $jiraIssueRepository,
        private IssueHtmlProcessor $htmlProcessor,
    ) {
    }

    #[Route(
        path: '/{keyIssue}',
        name: RouteCollection::VIEW->value,
        methods: [Request::METHOD_GET, Request::METHOD_POST],
    )]
    public function view(
        #[MapEntity(mapping: [
            'key' => 'jiraKey',
        ])]
        Project $project,
        string $keyIssue,
        Request $request,
        #[CurrentUser]
        User $user,
        #[MapQueryParameter]
        ?string $focusedCommentId = null,
    ): Response {
        $this->setCurrentProject($project);
        $issue = $this->jiraIssueRepository->getFull($keyIssue);
        $comments = $this->jiraIssueRepository->getCommentForIssue($keyIssue);

        $form = $this->createForm(IssueCommentFormType::class, new CreateComment($issue, '', [], $user));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->handle($form->getData());

            $this->addFlash(
                type: 'success',
                message: 'flash.created',
            );

            return $this->redirectToRoute(
                route: RouteCollection::VIEW->prefixed(),
                parameters: [
                    'key' => $this->getCurrentProject()
                        ->jiraKey,
                    'keyIssue' => $keyIssue,
                ],
            );
        }
        $links = [];
        foreach ($issue->fields->issuelinks as $link) {
            $linkIssue = $link->inwardIssue ?? $link->outwardIssue;
            $type = isset($link->inwardIssue) ? $link->type->inward : $link->type->outward;
            try {
                $fullLinkIssue = $this->jiraIssueRepository->getFull($linkIssue->id);
            } catch (JiraException $jiraException) {
                continue;
            }
            $links[] = [
                'type' => $type,
                'issue' => $fullLinkIssue,
            ];
        }

        return $this->render(
            view: 'app/project/issue/view.html.twig',
            parameters: [
                'key' => $keyIssue,
                'issue' => $issue,
                'project' => $project,
                'links' => $links,
                'comments' => $comments->comments,
                'commentForm' => $form->createView(),
                'focusedCommentId' => $focusedCommentId,
            ]
        );
    }

    #[Route(
        path: '/{keyIssue}/attachments-stream',
        name: RouteCollection::VIEW_ATTACHMENTS_STREAM->value,
        methods: [Request::METHOD_GET],
    )]
    public function viewAttachments(
        #[MapEntity(mapping: [
            'key' => 'jiraKey',
        ])]
        Project $project,
        string $keyIssue,
        Request $request,
    ): Response {
        $this->setCurrentProject($project);
        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
        $issue = $this->handle(new GetFullIssue($keyIssue));

        if (count($issue->customAttachments) == 0) {
            return $this->renderBlock(
                view: 'app/project/issue/issue_attachment.stream.html.twig',
                block: 'empty',
                parameters: [
                    'issue' => $issue,
                ]
            );
        }

        return $this->render(
            view: 'app/project/issue/issue_attachment.stream.html.twig',
            parameters: [
                'issue' => $issue,
            ]
        );
    }
}
