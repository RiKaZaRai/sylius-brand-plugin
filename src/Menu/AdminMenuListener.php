<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function __invoke(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $catalogMenu = $menu->getChild('catalog');
        if (null === $catalogMenu) {
            return;
        }

        $catalogMenu
            ->addChild('brands', [
                'route' => 'rika_sylius_brand_admin_brand_index', // Route qui sera générée par le fichier de routes
            ])
            ->setLabel('Marques')
            ->setLabelAttribute('icon', 'star')
        ;
    }
}