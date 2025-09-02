<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

interface ProductBrandAwareInterface
{
    public function getBrand(): ?BrandInterface;
    public function setBrand(?BrandInterface $brand): void;
}
