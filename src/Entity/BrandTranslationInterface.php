<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\Model\TranslationInterface;

interface BrandTranslationInterface extends TranslationInterface, ResourceInterface
{
    public function getName(): ?string;
    public function setName(?string $name): void;

    public function getSlug(): ?string;
    public function setSlug(?string $slug): void;

    public function getDescription(): ?string;
    public function setDescription(?string $description): void;

    public function getMetaKeywords(): ?string;
    public function setMetaKeywords(?string $metaKeywords): void;

    public function getMetaDescription(): ?string;
    public function setMetaDescription(?string $metaDescription): void;

    public function getBrand(): ?BrandInterface;
}
