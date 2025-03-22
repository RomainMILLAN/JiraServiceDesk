<?php

namespace App\Twig\Components\Navigation;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('nav:item-single')]
class MenuNavSingleComponent
{
    public ?string $path = null;

    public ?string $trans = null;

    public ?string $href = null;

    public ?string $icon = null;

    public ?string $role = null;

    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired('path');
        $resolver->setRequired('trans');
        $resolver->setRequired('href');
        $resolver->setRequired('icon');
        $resolver->setRequired('role');
        $resolver->setAllowedTypes('path', 'string');
        $resolver->setAllowedTypes('trans', 'string');
        $resolver->setAllowedTypes('href', 'string');
        $resolver->setAllowedTypes('icon', 'string');
        $resolver->setAllowedTypes('role', 'string');

        return $resolver->resolve($data);
    }

    public function getCurrentRoute(): string
    {
        if (null === $request = $this->requestStack->getMainRequest()) {
            return '';
        }

        return $request->attributes->get('_route');
    }
}
