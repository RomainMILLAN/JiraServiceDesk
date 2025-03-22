<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceSwitchType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('attr', [
            'class' => 'form-check form-switch ms-3 mb-3 p-0',
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
