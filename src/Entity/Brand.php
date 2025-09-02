<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Core\Model\ProductInterface;

#[ORM\Entity]
#[ORM\Table(name: 'rika_brand')]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
class Brand implements BrandInterface, ResourceInterface, TranslatableInterface, TimestampableInterface, SlugAwareInterface
{
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $code = null;

    #[ORM\Column(type: 'string', length: 500, nullable: true)]
    private ?string $logoPath = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $enabled = true;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Gedmo\SortablePosition]
    private ?int $position = 0;

    #[ORM\OneToMany(targetEntity: ProductInterface::class, mappedBy: 'brand', cascade: ['persist'])]
    private Collection $products;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $deletedAt = null;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->products = new ArrayCollection();
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

    public function getMetaTitle(): ?string
    {
        return $this->getTranslation()->getMetaTitle();
    }

    public function setMetaTitle(?string $metaTitle): void
    {
        $this->getTranslation()->setMetaTitle($metaTitle);
    }

    public function getMetaDescription(): ?string
    {
        return $this->getTranslation()->getMetaDescription();
    }

    public function setMetaDescription(?string $metaDescription): void
    {
        $this->getTranslation()->setMetaDescription($metaDescription);
    }

    public function getLogoPath(): ?string
    {
        return $this->logoPath;
    }

    public function setLogoPath(?string $logoPath): void
    {
        $this->logoPath = $logoPath;
    }

    public function isEnabled(): bool
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

    public function addProduct(ProductInterface $product): void
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            if (method_exists($product, 'setBrand')) {
                $product->setBrand($this);
            }
        }
    }

    public function removeProduct(ProductInterface $product): void
    {
        if ($this->products->removeElement($product)) {
            if (method_exists($product, 'setBrand')) {
                $product->setBrand(null);
            }
        }
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    protected function createTranslation(): BrandTranslationInterface
    {
        return new BrandTranslation();
    }
}
