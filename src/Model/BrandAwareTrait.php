<?php
// src/Model/BrandAwareTrait.php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Model;

use Rika\SyliusBrandPlugin\Entity\BrandInterface;

trait BrandAwareTrait
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
