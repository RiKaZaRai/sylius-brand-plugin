<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Form\Type;

use Rika\SyliusBrandPlugin\Entity\BrandInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationType;

final class BrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'sylius.ui.code',
            ])
            ->add('logoPath', FileType::class, [
                'label' => 'rika_sylius_brand.ui.logo',
                'required' => false,
                'mapped' => false, // On gère l'upload dans EventListener
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.ui.enabled',
                'required' => false,
            ])
            ->add('position', IntegerType::class, [
                'label' => 'rika_sylius_brand.ui.position',
                'required' => false,
            ])
            ->add('translations', ResourceTranslationType::class, [
                'entry_type' => BrandTranslationType::class,
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BrandInterface::class,
        ]);
    }
}
