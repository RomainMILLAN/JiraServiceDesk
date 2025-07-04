<?php

namespace App\Service;

use Symfony\Component\Routing\RouterInterface;

readonly class IssueHtmlProcessor
{
    public function __construct(
        private RouterInterface $router,
    ) {
    }

    public function updateImageSources(string $html): string
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $images = $dom->getElementsByTagName('img');
        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            if (preg_match('#/rest/api/3/attachment/content/(\d+)#', $src, $matches)) {
                $attachmentId = $matches[1];
                $newUrl = $this->router->generate('app_attachment', [
                    'attachmentId' => $attachmentId,
                ]);
                $img->setAttribute('src', $newUrl);
            }

            $img->removeAttribute('height');
            $style = $img->getAttribute('style');
            if (stripos($style, 'max-width') === false) {
                $style = rtrim($style, '; ') . '; max-width: 100%;';
                $img->setAttribute('style', $style);
            }
        }

        return $dom->saveHTML();
    }

    public function updateJiraLinks(string $html): string
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $links = $dom->getElementsByTagName('a');
        foreach ($links as $link) {
            $href = $link->getAttribute('href');

            // Match links like https://something.atlassian.net/browse/MMGS-96
            if (preg_match('#https:\/\/([a-zA-Z0-9\-]+)\.atlassian\.net\/browse\/([A-Z]+)-(\d+)#', $href, $matches)) {
                $domain = $matches[1];               // e.g., spiriit
                $projectKey = $matches[2];           // e.g., MMGS
                $issueNumber = $matches[3];          // e.g., 96
                $issueKey = $projectKey . '-' . $issueNumber;

                $focusedCommentId = null;
                if (preg_match('#focusedCommentId=(\d+)#', $href, $commentIds)) {
                    $focusedCommentId = $commentIds[1];
                }

                // Generate custom route like /jira/issue/MMGS/MMGS-96
                $newUrl = $this->router->generate('browse_issue', [
                    'keyIssue' => $issueKey,
                    'focusedCommentId' => $focusedCommentId,
                ]);

                $link->setAttribute('href', $newUrl);
            }
        }

        return $dom->saveHTML();
    }
}
