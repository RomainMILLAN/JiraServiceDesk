<?php

declare(strict_types=1);

namespace App\Twig\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('amount', [$this, 'formatAmount']),
        ];
    }

    public function formatAmount($number, $decimals = 2, $decPoint = ',', $thousandsSep = ' '): string
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);

        return sprintf('%s %s', $price, '€');
    }
}
