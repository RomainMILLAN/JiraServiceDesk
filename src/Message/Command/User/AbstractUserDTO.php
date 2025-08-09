<?php

namespace App\Message\Command\User;

use App\Entity\Project;
use App\Enum\User\Locale;
use App\Enum\User\Theme;

class AbstractUserDTO
{
    /**
     * @param array<int,mixed> $roles
     * @param array<int,mixed> $projects
     */
    public function __construct(
        public string $email,
        public string $lastName,
        public string $firstName,
        public array $roles = [],
        public array $projects = [],
        public ?string $plainPassword = null,
        public ?string $company = null,
        public Locale $preferedLocale = Locale::FR,
        public Theme $preferedTheme = Theme::AUTO,
        public bool $preferenceNotification = true,
        public bool $preferenceNotificationIssueCreated = true,
        public bool $preferenceNotificationIssueUpdated = true,
        public bool $preferenceNotificationCommentCreated = true,
        public bool $preferenceNotificationCommentUpdated = true,
        public bool $preferenceNotificationCommentOnlyOnTag = false,
        public bool $enabled = true,
        public ?Project $defaultProject = null,
    ) {
    }
}
