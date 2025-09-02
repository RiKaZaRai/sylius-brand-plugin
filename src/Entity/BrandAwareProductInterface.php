<?php
// src/Entity/BrandAwareProductInterface.php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Sylius\Component\Product\Model\ProductInterface;

interface BrandAwareProductInterface extends ProductInterface
{
    public function getBrand(): ?BrandInterface;

    public function setBrand(?BrandInterface $brand): void;
}
