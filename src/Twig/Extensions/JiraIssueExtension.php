<?php

namespace App\Twig\Extensions;

use App\Service\IssueHtmlProcessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class JiraIssueExtension extends AbstractExtension
{
    public function __construct(
        private readonly IssueHtmlProcessor $htmlProcessor,
    ) {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('preview_attachment', $this->previewAttachmentFormat(...)),
            new TwigFilter('parse_comment_author', $this->parseCommentAuthor(...)),
        ];
    }

    public function previewAttachmentFormat($renderedDescription): string
    {
        return $this->htmlProcessor->updateImageSources($renderedDescription);
    }

    public function parseCommentAuthor($comment): ?string
    {
        $parts = explode('—', html_entity_decode($comment->renderedBody));
        if (count($parts) > 1) {
            $author = trim(array_pop($parts));
            $author = strip_tags(str_ireplace(['<br>', '<br/>', '<br />'], '', $author));

            return $author;
        }

        return null;
    }
}
