<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\Model\TranslatableInterface;
use Sylius\Resource\Model\TimestampableInterface;

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
    public function getLogoPath(): ?string;
    public function setLogoPath(?string $logoPath): void;
    public function isEnabled(): bool;
    public function setEnabled(bool $enabled): void;
    public function getPosition(): ?int;
    public function setPosition(?int $position): void;
}
