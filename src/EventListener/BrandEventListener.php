<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\EventListener;

use Rika\SyliusBrandPlugin\Entity\BrandInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class BrandEventListener
{
    public const BRAND_PRE_CREATE = 'rika_sylius_brand.brand.pre_create';
    public const BRAND_POST_CREATE = 'rika_sylius_brand.brand.post_create';
    public const BRAND_PRE_UPDATE = 'rika_sylius_brand.brand.pre_update';
    public const BRAND_POST_UPDATE = 'rika_sylius_brand.brand.post_update';
    public const BRAND_PRE_DELETE = 'rika_sylius_brand.brand.pre_delete';
    public const BRAND_POST_DELETE = 'rika_sylius_brand.brand.post_delete';

    public function __construct(
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function onBrandPreCreate(GenericEvent $event): void
    {
        /** @var BrandInterface $brand */
        $brand = $event->getSubject();
        
        $this->eventDispatcher->dispatch(
            new GenericEvent($brand, ['action' => 'pre_create']),
            'rika_sylius_brand.hook.brand_pre_create'
        );
    }

    public function onBrandPostCreate(GenericEvent $event): void
    {
        /** @var BrandInterface $brand */
        $brand = $event->getSubject();
        
        $this->eventDispatcher->dispatch(
            new GenericEvent($brand, ['action' => 'post_create']),
            'rika_sylius_brand.hook.brand_post_create'
        );
    }

    public function onBrandPreUpdate(GenericEvent $event): void
    {
        /** @var BrandInterface $brand */
        $brand = $event->getSubject();
        
        $this->eventDispatcher->dispatch(
            new GenericEvent($brand, ['action' => 'pre_update']),
            'rika_sylius_brand.hook.brand_pre_update'
        );
    }

    public function onBrandPostUpdate(GenericEvent $event): void
    {
        /** @var BrandInterface $brand */
        $brand = $event->getSubject();
        
        $this->eventDispatcher->dispatch(
            new GenericEvent($brand, ['action' => 'post_update']),
            'rika_sylius_brand.hook.brand_post_update'
        );
    }
}
