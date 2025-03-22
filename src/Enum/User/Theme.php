<?php

namespace App\Enum\User;

use App\Enum\Contracts\LabeledValueInterface;
use App\Enum\Trait\ValueCasesTrait;

enum Theme: string implements LabeledValueInterface
{
    use ValueCasesTrait;

    case LIGHT = 'light';
    case DARK = 'dark';
    case AUTO = 'auto';

    public function label(): string
    {
        return sprintf('theme.%s', mb_strtolower($this->name));
    }
}
