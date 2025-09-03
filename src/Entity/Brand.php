<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\Model\SlugAwareInterface;
use Sylius\Resource\Model\TimestampableTrait;
use Sylius\Resource\Model\ToggleableTrait;
use Sylius\Resource\Model\TranslatableTrait;
use Sylius\Resource\Model\TranslatableInterface;
use Sylius\Resource\Model\TranslationInterface;

class Brand implements BrandInterface, ResourceInterface, SlugAwareInterface, TranslatableInterface
{
    use TimestampableTrait;
    use ToggleableTrait;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }

    protected ?int $id = null;
    protected ?string $code = null;
    protected ?string $logoPath = null;
    protected ?int $position = null;
    protected Collection $products;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->products = new ArrayCollection();
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

    public function getLogoPath(): ?string
    {
        return $this->logoPath;
    }

    public function setLogoPath(?string $logoPath): void
    {
        $this->logoPath = $logoPath;
    }

    /**
     * Alias pour logoPath - utilisé par la grid
     */
    public function getLogo(): ?string
    {
        return $this->logoPath;
    }

    /**
     * Alias pour logoPath - utilisé par la grid
     */
    public function setLogo(?string $logo): void
    {
        $this->logoPath = $logo;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function setEnabled(?bool $enabled): void
    {
        $this->enabled = $enabled ?? false;
    }

    /**
     * @return Collection<int, ProductInterface>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function hasProduct(ProductInterface $product): bool
    {
        return $this->products->contains($product);
    }

    public function addProduct(ProductInterface $product): void
    {
        if (!$this->hasProduct($product)) {
            $this->products->add($product);
            $product->setBrand($this);
        }
    }

    public function removeProduct(ProductInterface $product): void
    {
        if ($this->hasProduct($product)) {
            $this->products->removeElement($product);
            $product->setBrand(null);
        }
    }

    // Méthodes de traduction déléguées à BrandTranslation
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

    public function getMetaKeywords(): ?string
    {
        return $this->getTranslation()->getMetaKeywords();
    }

    public function setMetaKeywords(?string $metaKeywords): void
    {
        $this->getTranslation()->setMetaKeywords($metaKeywords);
    }

    public function getMetaDescription(): ?string
    {
        return $this->getTranslation()->getMetaDescription();
    }

    public function setMetaDescription(?string $metaDescription): void
    {
        $this->getTranslation()->setMetaDescription($metaDescription);
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslation(?string $locale = null): TranslationInterface
    {
        /** @var BrandTranslationInterface $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation(): TranslationInterface
    {
        return new BrandTranslation();
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }
}
