<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

trait ProductBrandAwareTrait
{
    protected ?BrandInterface $brand = null;

    public function getBrand(): ?BrandInterface
    {
        return $this->brand;
    }

    public function setBrand(?BrandInterface $brand): void
    {
        $this->brand = $brand;
    }
}
