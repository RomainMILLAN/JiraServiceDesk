<?php

namespace App\Form\Admin\User;

use App\Form\Type\SwitchType;
use Symfony\Component\Form\FormBuilderInterface;

class
UserProfileFormType extends AbstractUserFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
    }
}
