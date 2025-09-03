<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Form\Type;

use Rika\SyliusBrandPlugin\Entity\Brand;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BrandType extends AbstractResourceType
{
    public function __construct(
        string $dataClass,
        array $validationGroups,
        private LocaleProviderInterface $localeProvider,
        private LocaleContextInterface $localeContext
    ) {
        parent::__construct($dataClass, $validationGroups);
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
            'locales' => $this->localeProvider->getAvailableLocalesCodes(),
            'default_locale' => $this->localeContext->getLocaleCode(),
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'rika_brand';
    }
}