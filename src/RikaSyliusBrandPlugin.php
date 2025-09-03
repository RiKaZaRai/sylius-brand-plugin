<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin;

use Rika\SyliusBrandPlugin\DependencyInjection\RikaSyliusBrandExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class RikaSyliusBrandPlugin extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new RikaSyliusBrandExtension();
    }

    public function configureRoutes(RoutingConfigurator $routes, ?string $environment): void
    {
        $routes->import($this->getPath() . '/Resources/config/routes.yaml');
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}