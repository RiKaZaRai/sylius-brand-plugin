<?php

namespace Rika\SyliusBrandPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addBrandMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $catalogMenu = $menu->getChild('catalog');
        
        if (null !== $catalogMenu) {
            $catalogMenu
                ->addChild('brands', [
                    'route' => 'rika_sylius_brand_admin_brand_index', // Le bon nom de route !
                ])
                ->setLabel('rika_sylius_brand_plugin.ui.brands')
                ->setLabelAttribute('icon', 'trademark');
        }
    }
}
