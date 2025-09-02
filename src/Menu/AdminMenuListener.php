<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addBrandMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $menu
            ->addChild('brands', ['route' => 'sylius_admin_rika_sylius_brand_index'])
            ->setLabel('rika_sylius_brand.ui.brands')
            ->setLabelAttribute('icon', 'tags');
    }
}
