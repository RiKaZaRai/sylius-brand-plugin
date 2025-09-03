<?php
// src/Form/Extension/ProductTypeExtension.php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Form\Extension;

use Rika\SyliusBrandPlugin\Form\Type\BrandChoiceType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class ProductTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('brand', BrandChoiceType::class, [
            'required' => false,
            'label' => 'rika_sylius_brand.ui.brand',
            'placeholder' => 'rika_sylius_brand.form.product.select_brand',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [ProductType::class];
    }
}
