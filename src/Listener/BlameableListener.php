<?php

declare(strict_types=1);

namespace App\Listener;

use App\Entity\User;
use Gedmo\Blameable\BlameableListener as BaseBlameableListener;
use Gedmo\Exception\InvalidArgumentException;

/** @phpstan-ignore-next-line  */
class BlameableListener extends BaseBlameableListener
{
    #[\Override]
    public function getFieldValue($meta, $field, $eventAdapter)
    {
        if ($meta->hasAssociation($field)) {
            if ($this->user !== null && ! is_object($this->user)) {
                throw new InvalidArgumentException('Blame is reference, user must be an object');
            }

            return $this->user;
        }

        if ($this->user instanceof User) {
            return $this->user->getLoggable();
        }

        return null;
    }
}
