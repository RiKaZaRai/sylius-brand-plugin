<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Sylius\Resource\Model\ResourceInterface;      // ✅ Nouveau namespace
use Sylius\Resource\Model\TranslationInterface;   // ✅ Nouveau namespace

interface BrandTranslationInterface extends ResourceInterface, TranslationInterface
{
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
}
