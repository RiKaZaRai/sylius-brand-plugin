<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Form\Type;

use Rika\SyliusBrandPlugin\Entity\Brand;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

final class BrandType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'rika_sylius_brand.form.brand.code',
                'required' => true,
            ])
            // Suppression temporaire des traductions
            ->add('name', TextType::class, [
                'label' => 'rika_sylius_brand.form.brand.name',
                'mapped' => false,
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'rika_sylius_brand.form.brand.description',
                'mapped' => false,
                'required' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'rika_sylius_brand.form.brand.enabled',
                'required' => false,
            ])
            ->add('position', IntegerType::class, [
                'label' => 'rika_sylius_brand.form.brand.position',
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'rika_brand';
    }
}