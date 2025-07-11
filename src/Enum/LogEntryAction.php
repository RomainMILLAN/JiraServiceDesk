<?php

namespace App\Enum;

use App\Enum\Contracts\LabeledValueInterface;

enum LogEntryAction: string implements LabeledValueInterface
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case READ = 'read';

    public function label(): string
    {
        return sprintf('log.action.%s', mb_strtolower($this->name));
    }
}
