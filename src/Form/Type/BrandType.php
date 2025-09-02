<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BrandType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'rika_sylius_brand.form.brand.code',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'rika_sylius_brand.form.brand.enabled',
                'required' => false,
            ])
            ->add('position', IntegerType::class, [
                'label' => 'rika_sylius_brand.form.brand.position',
                'required' => false,
            ])
            ->add('logoFile', FileType::class, [
                'label' => 'rika_sylius_brand.form.brand.logo',
                'required' => false,
                'mapped' => false,
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => BrandTranslationType::class,
                'label' => 'rika_sylius_brand.form.brand.translations',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'rika_sylius_brand';
    }
}
