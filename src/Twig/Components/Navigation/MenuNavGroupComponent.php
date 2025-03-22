<?php

namespace App\Twig\Components\Navigation;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('nav:item-group')]
class MenuNavGroupComponent
{
    public ?string $path = null;

    public ?string $firstLevelTrans = null;

    public ?string $icon = null;

    public ?string $role = null;

    public ?bool $collapse = true;

    public array $secondLevel = [];

    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired('path');
        $resolver->setRequired('firstLevelTrans');
        $resolver->setRequired('icon');
        $resolver->setRequired('role');
        $resolver->setDefault('collapse', true);
        $resolver->setDefault('secondLevel', []);
        $resolver->setAllowedTypes('path', ['string']);
        $resolver->setAllowedTypes('secondLevel', 'array');
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

    public function getSubMenuKey(): string
    {
        if ($this->path === null) {
            return '';
        }

        return (new AsciiSlugger())->slug($this->path)
            ->toString()
        ;
    }
}
