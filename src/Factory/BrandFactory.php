<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Factory;

use Rika\SyliusBrandPlugin\Entity\BrandInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Resource\Factory\FactoryInterface;

final class BrandFactory implements FactoryInterface
{
    public function __construct(
        private FactoryInterface $decoratedFactory,
        private LocaleContextInterface $localeContext
    ) {
    }

    public function createNew(): BrandInterface
    {
        /** @var BrandInterface $brand */
        $brand = $this->decoratedFactory->createNew();

        try {
            $localeCode = $this->localeContext->getLocaleCode();
            $brand->setCurrentLocale($localeCode);
            $brand->setFallbackLocale($localeCode);
        } catch (\Exception $e) {
            // Fallback sur une locale par défaut
            $brand->setCurrentLocale('fr_FR');
            $brand->setFallbackLocale('fr_FR');
        }

        return $brand;
    }
}
