<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Rika\SyliusBrandPlugin\Entity\BrandInterface;

class ProductMappingListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();
        
        // Extend Product entity dynamically
        if ($this->isProductEntity($classMetadata)) {
            $this->mapBrandToProduct($classMetadata);
        }
    }

    private function isProductEntity(ClassMetadata $classMetadata): bool
    {
        return in_array('Sylius\Component\Product\Model\ProductInterface', class_implements($classMetadata->getName()) ?: []);
    }

    private function mapBrandToProduct(ClassMetadata $classMetadata): void
    {
        if (!$classMetadata->hasAssociation('brand')) {
            $classMetadata->mapManyToOne([
                'fieldName' => 'brand',
                'targetEntity' => BrandInterface::class,
                'joinColumns' => [[
                    'name' => 'brand_id',
                    'referencedColumnName' => 'id',
                    'nullable' => true,
                ]],
            ]);
        }
    }
}
