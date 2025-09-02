<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BrandTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'rika_sylius_brand.form.brand.name',
            ])
            ->add('slug', TextType::class, [
                'label' => 'rika_sylius_brand.form.brand.slug',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'rika_sylius_brand.form.brand.description',
                'required' => false,
            ])
            ->add('metaTitle', TextType::class, [
                'label' => 'rika_sylius_brand.form.brand.meta_title',
                'required' => false,
            ])
            ->add('metaDescription', TextareaType::class, [
                'label' => 'rika_sylius_brand.form.brand.meta_description',
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'rika_sylius_brand_translation';
    }
}
