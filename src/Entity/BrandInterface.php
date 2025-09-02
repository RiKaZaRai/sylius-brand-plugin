<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Resource\Model\ToggleableInterface;
use Sylius\Resource\Model\TimestampableInterface;
use Sylius\Resource\Model\TranslationInterface;

interface BrandInterface extends ToggleableInterface, TimestampableInterface
{
    public function getId(): ?int;

    public function getCode(): ?string;
    public function setCode(?string $code): void;

    public function getLogoPath(): ?string;
    public function setLogoPath(?string $logoPath): void;

    public function getPosition(): ?int;
    public function setPosition(?int $position): void;

    /**
     * @return ProductInterface[]
     */
    public function getProducts(): iterable;
    public function addProduct(ProductInterface $product): void;
    public function removeProduct(ProductInterface $product): void;
    public function hasProduct(ProductInterface $product): bool;

    public function getName(): ?string;
    public function setName(?string $name): void;

    public function getDescription(): ?string;
    public function setDescription(?string $description): void;

    public function getMetaKeywords(): ?string;
    public function setMetaKeywords(?string $metaKeywords): void;

    public function getMetaDescription(): ?string;
    public function setMetaDescription(?string $metaDescription): void;

    public function getSlug(): ?string;
    public function setSlug(?string $slug): void;

    public function getTranslation(?string $locale = null): TranslationInterface;
}
