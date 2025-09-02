<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface BrandInterface extends 
    ResourceInterface, 
    TranslatableInterface, 
    TimestampableInterface, 
    ToggleableInterface,
    SlugAwareInterface
{
    public function id(): ?int;

    public function code(): ?string;

    public function setCode(?string $code): void;

    public function logoPath(): ?string;

    public function setLogoPath(?string $logoPath): void;

    public function position(): ?int;

    public function setPosition(?int $position): void;

    public function products(): Collection;

    public function addProduct(ProductInterface $product): void;

    public function removeProduct(ProductInterface $product): void;

    public function hasProduct(ProductInterface $product): bool;

    // Méthodes de délégation pour les traductions
    public function name(): ?string;

    public function setName(?string $name): void;

    public function slug(): ?string;

    public function setSlug(?string $slug): void;

    public function description(): ?string;

    public function setDescription(?string $description): void;

    public function metaKeywords(): ?string;

    public function setMetaKeywords(?string $metaKeywords): void;

    public function metaDescription(): ?string;

    public function setMetaDescription(?string $metaDescription): void;
}
