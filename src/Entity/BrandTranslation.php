<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Sylius\Resource\Model\AbstractTranslation;

class BrandTranslation extends AbstractTranslation implements BrandTranslationInterface
{
    private ?int $id = null;
    
    // ❌ NE PAS REDÉCLARER cette propriété, elle existe déjà dans AbstractTranslation
    // protected ?BrandInterface $translatable = null; 
    
    private ?string $name = null;
    
    private ?string $slug = null;
    
    private ?string $description = null;

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

    // ✅ Méthode helper pour avoir le bon type
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
}
