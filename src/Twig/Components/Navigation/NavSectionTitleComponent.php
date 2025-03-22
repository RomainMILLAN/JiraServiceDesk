<?php

namespace App\Twig\Components\Navigation;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent(name: 'nav:section-title', template: 'components/nav/section-title.html.twig')]
class NavSectionTitleComponent
{
    public ?string $trans = null;

    public ?string $role = null;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired('trans');
        $resolver->setRequired('role');
        $resolver->setAllowedTypes('trans', 'string');
        $resolver->setAllowedTypes('role', 'string');

        return $resolver->resolve($data);
    }
}
