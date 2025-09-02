<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Resource\Model\TranslatableTrait;
use Sylius\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\SlugAwareInterface;

#[ORM\Entity]
#[ORM\Table(name: 'rika_brand')]
class Brand implements BrandInterface, SlugAwareInterface
{
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    protected ?string $code = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $logoPath = null;

    #[ORM\Column(type: 'boolean')]
    protected bool $enabled = true;

    #[ORM\Column(type: 'integer', nullable: true)]
    protected ?int $position = null;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: 'Sylius\Component\Core\Model\Product')]
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

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct($product): void
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setBrand($this);
        }
    }

    public function removeProduct($product): void
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->setBrand(null);
        }
    }

    // Méthodes pour les traductions
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

    protected function createTranslation(): BrandTranslationInterface
    {
        return new BrandTranslation();
    }

    public function __toString(): string
    {
        return $this->getName() ?? '';
    }
}
