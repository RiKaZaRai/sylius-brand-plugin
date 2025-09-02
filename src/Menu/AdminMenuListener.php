<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function __invoke(MenuBuilderEvent $event): void // Changé ici !
    {
        $menu = $event->getMenu();

        $catalog = $menu->getChild('catalog');
        if (null !== $catalog) {
            $catalog
                ->addChild('brands', [
                    'route' => 'rika_admin_brand_index',
                ])
                ->setLabel('rika.menu.admin.main.catalog.brands')
                ->setLabelAttribute('icon', 'trademark');
        }
    }
}