<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface BrandTranslationInterface extends ResourceInterface, TranslationInterface, SlugAwareInterface
{
    public function getName(): ?string;
    public function setName(?string $name): void;

    public function getDescription(): ?string;
    public function setDescription(?string $description): void;

    public function getMetaKeywords(): ?string;
    public function setMetaKeywords(?string $metaKeywords): void;

    public function getMetaDescription(): ?string;
    public function setMetaDescription(?string $metaDescription): void;
}
