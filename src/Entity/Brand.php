<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Sylius\Resource\Model\AbstractTranslatable;    // ✅ Nouveau
use Sylius\Resource\Model\TimestampableTrait;      // ✅ Nouveau

class Brand extends AbstractTranslatable implements BrandInterface
{
    use TimestampableTrait;

    protected ?int $id = null;
    protected ?string $code = null;
    protected bool $enabled = true;

    public function __construct()
    {
        parent::__construct();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getName(): ?string
    {
        return $this->getTranslation()->getName();
    }

    public function setName(?string $name): void
    {
        $this->getTranslation()->setName($name);
    }

    public function getSlug(): ?string
    {
        return $this->getTranslation()->getSlug();
    }

    public function setSlug(?string $slug): void
    {
        $this->getTranslation()->setSlug($slug);
    }

    public function getDescription(): ?string
    {
        return $this->getTranslation()->getDescription();
    }

    public function setDescription(?string $description): void
    {
        $this->getTranslation()->setDescription($description);
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    protected function createTranslation(): BrandTranslationInterface
    {
        return new BrandTranslation();
    }
}
