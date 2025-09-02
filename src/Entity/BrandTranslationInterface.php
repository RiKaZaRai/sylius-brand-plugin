<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface BrandTranslationInterface extends ResourceInterface, TranslationInterface, SlugAwareInterface
{
    public function id(): ?int;

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
