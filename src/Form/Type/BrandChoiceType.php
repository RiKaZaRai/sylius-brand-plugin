<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\ResourceChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BrandChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'resource' => 'rika_sylius_brand.brand', // Corrigé ici !
            'choice_label' => 'name',
            'choice_value' => 'id',
            'placeholder' => 'rika_sylius_brand.form.product.select_brand',
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