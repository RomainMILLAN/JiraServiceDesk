<?php

namespace App\Form\Admin\User;

use App\Enum\User\Role;
use App\Form\Type\ChoiceSwitchType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminUserFormType extends AbstractUserFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('roles', ChoiceSwitchType::class, [
                'multiple' => true,
                'required' => true,
                'expanded' => true,
                'choices' => Role::getList(),
                'choice_label' => function ($choice, $key, $value) {
                    return $choice;
                },
            ])
        ;
    }
}
