<?php

declare(strict_types=1);

namespace App\Enum\User;

final class Role
{
    public const string ROLE_ADMIN = 'ROLE_ADMIN';

    public const string ROLE_USER = 'ROLE_USER';

    public static function getList(): array
    {
        return [
            'admin' => self::ROLE_ADMIN,
            'user' => self::ROLE_USER,
        ];
    }
}
