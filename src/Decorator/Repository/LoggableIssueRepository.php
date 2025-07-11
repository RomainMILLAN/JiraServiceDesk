<?php

namespace App\Decorator\Repository;

use App\Entity\LogEntry;
use App\Enum\LogEntryAction;
use App\Repository\Contracts\Jira\IssueRepositoryInterface;
use App\Repository\Jira\IssueRepository as BaseIssueRepository;
use Doctrine\ORM\EntityManagerInterface;
use JiraCloud\Issue\Comment;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;

#[AsDecorator(decorates: BaseIssueRepository::class)]
class LoggableIssueRepository implements IssueRepositoryInterface
{
    public function __construct(
        #[AutowireDecorated]
        private readonly BaseIssueRepository $baseIssueRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
    ) {
    }

    public function create(\JiraCloud\Issue\IssueField $issueField): \JiraCloud\Issue\Issue
    {
        $issue = $this->baseIssueRepository->create($issueField);

        $this->entityManager->persist(
            new LogEntry(
                action: LogEntryAction::CREATE,
                type: 'issue',
                objectId: $issue->id,
                user: $this->security->getUser(),
                context: [
                    'summary' => $issueField->summary,
                    'issue_type' => $issueField->getIssueType()
                        ->description,
                    'reporter' => $issueField?->reporter?->toString() ?? 'empty',
                    'assignee' => $issueField?->reporter?->toString() ?? 'empty',
                    'priority' => $issueField->priority->name,
                    'status' => $issueField->status->name,
                    'labels' => json_encode($issueField->labels),
                    'project' => $issueField->project->key,
                ],
            ),
        );
        $this->entityManager->flush();

        return $issue;
    }

    public function createAttachment(string $id, string $filePath): array
    {
        $this->entityManager->persist(
            new LogEntry(
                action: LogEntryAction::CREATE,
                type: 'attachment',
                objectId: $id,
                user: $this->security->getUser(),
                context: [
                    'issue_id' => $id,
                    'attachment_file_path' => $filePath,
                ],
            ),
        );
        $this->entityManager->flush();

        return $this->baseIssueRepository->createAttachment($id, $filePath);
    }

    public function createComment(string $id, Comment $comment): Comment
    {
        $this->entityManager->persist(
            new LogEntry(
                action: LogEntryAction::CREATE,
                type: 'comment',
                objectId: $id,
                user: $this->security->getUser(),
                context: [
                    'comment_body' => $comment->jsonSerialize(),
                ],
            ),
        );
        $this->entityManager->flush();

        return $this->baseIssueRepository->createComment($id, $comment);
    }

    public function getComment(string $issueId, string $commentId): Comment
    {
        return $this->baseIssueRepository->getComment($issueId, $commentId);
    }

    public function getCommentForIssue(string $issueId): \JiraCloud\Issue\Comments
    {
        return $this->baseIssueRepository->getCommentForIssue($issueId);
    }

    public function getFull(string $issueId): \JiraCloud\Issue\Issue
    {
        return $this->baseIssueRepository->getFull($issueId);
    }

    public function transitionTo(string $id, string $transitionId): void
    {
        $this->entityManager->persist(
            new LogEntry(
                action: LogEntryAction::UPDATE,
                type: 'comment',
                objectId: $id,
                user: $this->security->getUser(),
                context: [
                    'transition_to_id' => $transitionId,
                ],
            ),
        );
        $this->entityManager->flush();

        $this->baseIssueRepository->transitionTo($id, $transitionId);
    }

    public function update(\JiraCloud\Issue\Issue $issue, \JiraCloud\Issue\IssueField $issueField): ?string
    {
        $this->entityManager->persist(
            new LogEntry(
                action: LogEntryAction::UPDATE,
                type: 'issue',
                objectId: $issue->id,
                user: $this->security->getUser(),
                context: [
                    'summary' => $issueField->summary,
                    'issue_type' => $issueField->getIssueType()
                        ->description,
                    'reporter' => $issueField?->reporter?->toString() ?? 'empty',
                    'assignee' => $issueField?->reporter?->toString() ?? 'empty',
                    'priority' => $issueField->priority->name,
                    'status' => $issueField->status->name,
                    'labels' => json_encode($issueField->labels),
                    'project' => $issueField->project->key,
                ],
            ),
        );
        $this->entityManager->flush();

        return $this->baseIssueRepository->update($issue, $issueField);
    }
}
