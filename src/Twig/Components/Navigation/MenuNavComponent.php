<?php

namespace App\Twig\Components\Navigation;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('nav:item')]
class MenuNavComponent
{
    public ?string $path = null;

    public ?string $trans = null;

    public ?string $href = null;

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
        $resolver->setAllowedTypes('path', ['string']);
        $resolver->setAllowedTypes('trans', ['string']);
        $resolver->setAllowedTypes('href', ['string']);

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
