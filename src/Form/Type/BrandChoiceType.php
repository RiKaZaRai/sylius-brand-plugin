<?php
// src/Form/Type/BrandChoiceType.php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Form\Type;

use Rika\SyliusBrandPlugin\Entity\BrandInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BrandChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'resource' => 'rika_sylius_brand_plugin.brand',
            'choice_label' => 'name',
            'choice_value' => 'id',
            'placeholder' => 'rika_sylius_brand_plugin.ui.choose_brand',
        ]);
    }

    public function getParent(): string
    {
        return ResourceChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'rika_sylius_brand_choice';
    }
}
