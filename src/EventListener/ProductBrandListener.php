<?php
// src/EventListener/ProductBrandListener.php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\EventListener;

use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Rika\SyliusBrandPlugin\Entity\BrandInterface;
use Sylius\Component\Product\Model\ProductInterface;

final class ProductBrandListener implements EventSubscriber
{
    private array $brandData = [];

    public function getSubscribedEvents(): array
    {
        return [
            Events::postLoad,
        ];
    }

    public function postLoad(PostLoadEventArgs $args): void
    {
        $entity = $args->getObject();
        
        if (!$entity instanceof ProductInterface) {
            return;
        }

        // Charger la relation brand si elle existe
        $entityManager = $args->getObjectManager();
        $metadata = $entityManager->getClassMetadata(get_class($entity));
        
        if ($metadata->hasAssociation('brand')) {
            $brand = $entityManager->getRepository(BrandInterface::class)
                ->findOneBy(['id' => $this->getBrandId($entity)]);
            
            $this->brandData[spl_object_id($entity)] = $brand;
        }
    }

    private function getBrandId(ProductInterface $product): ?int
    {
        // Utiliser la réflexion pour accéder à brand_id
        $reflection = new \ReflectionClass($product);
        
        // Cette méthode dépend de votre implémentation ORM
        // Vous pourriez avoir besoin d'adapter selon votre mapping
        
        return null; // À implémenter selon vos besoins
    }
}
