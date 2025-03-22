<?php

namespace App\Enum\User;

use App\Enum\Contracts\LabeledValueInterface;
use App\Enum\Trait\ValueCasesTrait;

enum Locale: string implements LabeledValueInterface
{
    use ValueCasesTrait;

    case FR = 'fr';
    case EN = 'en';

    public function label(): string
    {
        return sprintf('locale.%s', mb_strtolower($this->name));
    }
}
