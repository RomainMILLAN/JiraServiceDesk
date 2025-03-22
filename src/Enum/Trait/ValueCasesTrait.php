<?php

declare(strict_types=1);

namespace App\Enum\Trait;

trait ValueCasesTrait
{
    public function equalsToValue(string $value): bool
    {
        return self::tryFrom($value) === $this;
    }

    public static function valueCases(): array
    {
        return array_map(
            fn (self $role) => $role->value,
            self::cases(),
        );
    }
}
