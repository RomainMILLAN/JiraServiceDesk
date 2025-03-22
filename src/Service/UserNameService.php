<?php

declare(strict_types=1);

namespace App\Service;

use function Symfony\Component\String\s;

class UserNameService
{
    public static function initials(?string $firstName = null, ?string $lastName = null): string
    {
        if ($firstName === null || $lastName === null) {
            return '??';
        }

        return (string) s($firstName[0] . $lastName[0])
            ->upper()
        ;
    }
}
