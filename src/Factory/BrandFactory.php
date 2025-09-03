<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Factory;

use Rika\SyliusBrandPlugin\Entity\Brand;
use Rika\SyliusBrandPlugin\Entity\BrandInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Resource\Factory\FactoryInterface;

final class BrandFactory implements FactoryInterface
{
    private FactoryInterface $decoratedFactory;
    private LocaleContextInterface $localeContext;

    public function __construct(
        FactoryInterface $decoratedFactory,
        LocaleContextInterface $localeContext
    ) {
        $this->decoratedFactory = $decoratedFactory;
        $this->localeContext = $localeContext;
    }

    public function createNew(): BrandInterface
    {
        /** @var BrandInterface $brand */
        $brand = $this->decoratedFactory->createNew();
        
        // Initialise avec la locale courante
        $currentLocale = $this->localeContext->getLocaleCode();
        $brand->setCurrentLocale($currentLocale);
        $brand->setFallbackLocale($currentLocale);

        return $brand;
    }
}