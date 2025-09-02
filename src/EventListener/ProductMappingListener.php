<?php
// src/EventListener/ProductMappingListener.php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Rika\SyliusBrandPlugin\Entity\BrandInterface;
use Sylius\Component\Product\Model\ProductInterface;

final class ProductMappingListener implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();

        // Vérifier que c'est bien l'entité Product de Sylius
        if (!$classMetadata->reflClass || !$classMetadata->reflClass->implementsInterface(ProductInterface::class)) {
            return;
        }

        // Éviter de mapper deux fois
        if ($classMetadata->hasAssociation('brand')) {
            return;
        }

        // Ajouter la relation ManyToOne vers Brand
        $classMetadata->mapManyToOne([
            'fieldName' => 'brand',
            'targetEntity' => BrandInterface::class,
            'joinColumns' => [[
                'name' => 'brand_id',
                'referencedColumnName' => 'id',
                'nullable' => true,
                'onDelete' => 'SET NULL',
            ]],
        ]);
    }
}
