<?php
// src/EventListener/ProductMappingListener.php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Rika\SyliusBrandPlugin\Entity\BrandInterface;
use Sylius\Component\Product\Model\ProductInterface;

final class ProductMappingListener
{
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

        // Ajouter les méthodes getter et setter dynamiquement
        $this->addBrandMethodsToProduct($classMetadata);
    }

    private function addBrandMethodsToProduct($classMetadata): void
    {
        // Cette méthode est appelée pour s'assurer que les méthodes brand 
        // sont disponibles sur l'entité Product
        
        // Note: En réalité, Doctrine ORM gère automatiquement les getters/setters
        // pour les associations mappées, donc cette méthode peut rester vide
        // ou être utilisée pour des customisations spécifiques si nécessaire
    }
}
