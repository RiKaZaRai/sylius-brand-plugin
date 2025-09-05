<?php
declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        // Récupérer le sous-menu Catalog existant
        $catalogMenu = $menu->getChild('catalog');
        
        if (null !== $catalogMenu) {
            $catalogMenu
                ->addChild('brands', [
                    'route' => 'rika_sylius_brand_admin_brand_index',
                ])
                ->setLabel('rika_sylius_brand.menu.admin.main.catalog.brands')
                ->setLabelAttribute('icon', 'tabler:star');
        }
    }
}