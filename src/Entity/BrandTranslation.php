<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Sylius\Resource\Model\AbstractTranslation;

class BrandTranslation extends AbstractTranslation implements BrandTranslationInterface
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $slug = null;
    private ?string $description = null;
    private ?string $metaKeywords = null;
    private ?string $metaDescription = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function slug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function metaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords(?string $metaKeywords): void
    {
        $this->metaKeywords = $metaKeywords;
    }

    public function metaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }

    // Méthode helper pour avoir le bon type
    public function getBrand(): ?BrandInterface
    {
        /** @var BrandInterface|null $translatable */
        $translatable = $this->getTranslatable();
        return $translatable;
    }

    // Méthodes de compatibilité pour les getters classiques
    public function getName(): ?string
    {
        return $this->name();
    }

    public function getSlug(): ?string
    {
        return $this->slug();
    }

    public function getDescription(): ?string
    {
        return $this->description();
    }

    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords();
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription();
    }
}
