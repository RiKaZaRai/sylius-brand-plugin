<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Form\Extension;

use Rika\SyliusBrandPlugin\Entity\BrandInterface;
use Sylius\Bundle\ProductBundle\Form\Type\ProductType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class ProductTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('brand', EntityType::class, [
            'class' => BrandInterface::class,
            'choice_label' => 'name',
            'label' => 'rika_sylius_brand.form.product.brand',
            'placeholder' => 'rika_sylius_brand.form.product.select_brand',
            'required' => false,
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [ProductType::class];
    }
}
