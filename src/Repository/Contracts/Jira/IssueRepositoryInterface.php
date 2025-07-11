<?php

namespace App\Repository\Contracts\Jira;

use JiraCloud\Issue\Comment;
use JiraCloud\Issue\Comments;
use JiraCloud\Issue\Issue;
use JiraCloud\Issue\IssueField;

interface IssueRepositoryInterface
{
    public function getFull(string $issueId): Issue;

    public function getCommentForIssue(string $issueId): Comments;

    public function createComment(string $id, Comment $comment): Comment;

    public function getComment(string $issueId, string $commentId): Comment;

    public function createAttachment(string $id, string $filePath): array;

    public function transitionTo(string $id, string $transitionId): void;

    public function create(IssueField $issueField): Issue;

    public function update(Issue $issue, IssueField $issueField): ?string;
}
