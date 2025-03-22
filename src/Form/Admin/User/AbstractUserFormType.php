<?php

namespace App\Form\Admin\User;

use App\Enum\User\Locale;
use App\Enum\User\Theme;
use App\Message\Command\User\AbstractUserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

abstract class AbstractUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => $options['creating'],
                'disabled' => ! $options['creating'],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('firstName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('plainPassword', TextType::class, [
                'label' => 'user.password.label',
                'required' => $options['creating'],
            ])
            ->add('preferedLocale', EnumType::class, [
                'required' => true,
                'class' => Locale::class,
                'constraints' => [
                    new NotBlank(),
                ],
                'choice_label' => fn (Locale $locale) => $locale->label(),
            ])
            ->add('preferedTheme', EnumType::class, [
                'required' => true,
                'class' => Theme::class,
                'constraints' => [
                    new NotBlank(),
                ],
                'choice_label' => fn (Theme $theme) => $theme->label(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AbstractUserDTO::class,
            'translation_domain' => 'app',
            'label_format' => 'user.%name%.label',
            'creating' => false,
        ]);
    }
}
