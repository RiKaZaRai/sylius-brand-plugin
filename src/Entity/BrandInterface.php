<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Core\Model\ProductInterface;

interface BrandInterface extends ResourceInterface, TranslatableInterface, TimestampableInterface, SlugAwareInterface
{
    public function getCode(): ?string;
    public function setCode(?string $code): void;
    public function getName(): ?string;
    public function setName(?string $name): void;
    public function getSlug(): ?string;
    public function setSlug(?string $slug): void;
    public function getDescription(): ?string;
    public function setDescription(?string $description): void;
    public function getMetaTitle(): ?string;
    public function setMetaTitle(?string $metaTitle): void;
    public function getMetaDescription(): ?string;
    public function setMetaDescription(?string $metaDescription): void;
    public function getLogoPath(): ?string;
    public function setLogoPath(?string $logoPath): void;
    public function isEnabled(): bool;
    public function setEnabled(bool $enabled): void;
    public function getPosition(): ?int;
    public function setPosition(?int $position): void;
    public function getProducts(): Collection;
    public function addProduct(ProductInterface $product): void;
    public function removeProduct(ProductInterface $product): void;
}
