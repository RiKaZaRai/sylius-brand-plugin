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

        /** @var ItemInterface $catalogMenu */
        $catalogMenu = $menu->getChild('catalog');

        if (null !== $catalogMenu) {
            $catalogMenu
                ->addChild('brands', ['route' => 'rika_sylius_brand_plugin_admin_brand_index'])
                ->setLabel('rika_sylius_brand_plugin.ui.brands')
                ->setLabelAttribute('icon', 'trademark');
        }
    }
}
