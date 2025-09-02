<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class BrandTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'sylius.ui.name',
                'required' => true,
            ])
            ->add('slug', TextType::class, [
                'label' => 'sylius.ui.slug',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'sylius.ui.description',
                'required' => false,
            ])
            ->add('metaKeywords', TextType::class, [
                'label' => 'sylius.ui.meta_keywords',
                'required' => false,
            ])
            ->add('metaDescription', TextareaType::class, [
                'label' => 'sylius.ui.meta_description',
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'rika_brand_translation';
    }
}