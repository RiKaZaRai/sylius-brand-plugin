<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function __invoke(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $catalog = $menu->getChild('catalog');
        if (null !== $catalog) {
            $catalog
                ->addChild('brands', [
                    'route' => 'rika_sylius_brand_admin_brand_index', // Route corrigée !
                ])
                ->setLabel('rika_sylius_brand.ui.brands')
                ->setLabelAttribute('icon', 'trademark');
        }
    }
}