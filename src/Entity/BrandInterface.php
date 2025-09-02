<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Sylius\Resource\Model\ResourceInterface;        // ✅ Nouveau
use Sylius\Resource\Model\TranslatableInterface;    // ✅ Nouveau  
use Sylius\Resource\Model\TimestampableInterface;   // ✅ Nouveau

interface BrandInterface extends ResourceInterface, TranslatableInterface, TimestampableInterface
{
    public function getCode(): ?string;
    public function setCode(?string $code): void;
    public function getName(): ?string;
    public function setName(?string $name): void;
    public function getSlug(): ?string;
    public function setSlug(?string $slug): void;
    public function getDescription(): ?string;
    public function setDescription(?string $description): void;
    public function getEnabled(): bool;
    public function setEnabled(bool $enabled): void;
}
