<?php
// src/DependencyInjection/RikaSyliusBrandExtension.php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class RikaSyliusBrandExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration([], $container);
        if ($configuration instanceof ConfigurationInterface) {
            $config = $this->processConfiguration($configuration, $configs);
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        // Configuration Doctrine pour les mappings
        if ($container->hasExtension('doctrine')) {
            $container->prependExtensionConfig('doctrine', [
                'orm' => [
                    'mappings' => [
                        'RikaSyliusBrandPlugin' => [
                            'type' => 'xml',
                            'dir' => __DIR__ . '/../Resources/config/doctrine',
                            'prefix' => 'Rika\SyliusBrandPlugin\Entity',
                            'alias' => 'RikaSyliusBrandPlugin',
                        ],
                    ],
                ],
            ]);
        }
    }
}
