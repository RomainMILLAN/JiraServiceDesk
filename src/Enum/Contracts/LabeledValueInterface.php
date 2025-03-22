<?php

declare(strict_types=1);

namespace App\Enum\Contracts;

interface LabeledValueInterface
{
    public function label(): string;
}
