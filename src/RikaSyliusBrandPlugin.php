<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class RikaSyliusBrandPlugin extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }

    public function configureRoutes(RoutingConfigurator $routes, ?string $environment): void
    {
        // Importe automatiquement toutes les routes définies dans ton plugin
        $routes->import($this->getPath() . '/Resources/config/routes.yaml');
    }
}
