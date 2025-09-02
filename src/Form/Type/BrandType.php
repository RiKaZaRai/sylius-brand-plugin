<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Form\Type;

use Rika\SyliusBrandPlugin\Entity\Brand;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BrandType extends AbstractResourceType
{
    private array $locales;
    private string $defaultLocale;

    public function __construct(
        string $dataClass,
        array $validationGroups,
        array $locales,
        string $defaultLocale
    ) {
        parent::__construct($dataClass, $validationGroups);

        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'rika_sylius_brand.form.brand.code',
                'required' => true,
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => BrandTranslationType::class,
                'label' => 'rika_sylius_brand.form.brand.translations',
                'locales' => $this->locales,
                'default_locale' => $this->defaultLocale,
            ])
            ->add('logoPath', FileType::class, [
                'label' => 'rika_sylius_brand.form.brand.logo',
                'required' => false,
                'mapped' => false,
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'locales' => $this->locales,
            'default_locale' => $this->defaultLocale,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'rika_brand';
    }
}
