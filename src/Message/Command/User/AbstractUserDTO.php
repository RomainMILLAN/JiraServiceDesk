<?php

namespace App\Message\Command\User;

use App\Enum\User\Locale;
use App\Enum\User\Theme;

class AbstractUserDTO
{
    public function __construct(
        public string $email,
        public string $lastName,
        public string $firstName,
        public array $roles = [],
        public ?string $plainPassword = null,
        public Locale $preferedLocale = Locale::FR,
        public Theme $preferedTheme = Theme::AUTO,
    ) {
    }
}
