<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Rika\SyliusBrandPlugin\DependencyInjection\RikaSyliusBrandExtension;

final class RikaSyliusBrandPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getContainerExtension(): RikaSyliusBrandExtension
    {
        return new RikaSyliusBrandExtension();
    }
}
