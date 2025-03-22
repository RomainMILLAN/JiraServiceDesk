<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SwitchType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('row_attr', [
            'class' => 'form-check form-switch ms-2 mb-3 p-0',
        ]);
        $resolver->setDefault('required', false);
    }

    public function getParent(): string
    {
        return CheckboxType::class;
    }
}
