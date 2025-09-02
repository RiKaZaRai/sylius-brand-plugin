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
        // Traiter la configuration si elle existe
        $configuration = $this->getConfiguration([], $container);
        if ($configuration instanceof ConfigurationInterface) {
            $this->processConfiguration($configuration, $configs);
        }

        // Charger uniquement les fichiers YAML existants dans Resources/config
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $yamlFiles = ['services.yaml', 'sylius_resource.yaml']; // liste explicite
        foreach ($yamlFiles as $file) {
            if (file_exists(__DIR__ . '/../Resources/config/' . $file)) {
                $loader->load($file);
            }
        }
    }

    public function prepend(ContainerBuilder $container): void
    {
        // Configurer Doctrine pour les mappings XML de ton plugin
        if ($container->hasExtension('doctrine')) {
            $container->prependExtensionConfig('doctrine', [
                'orm' => [
                    'mappings' => [
                        'RikaSyliusBrandPlugin' => [
                            'type'   => 'xml',
                            'dir'    => __DIR__ . '/../Resources/config/doctrine',
                            'prefix' => 'Rika\SyliusBrandPlugin\Entity',
                            'alias'  => 'RikaSyliusBrandPlugin',
                        ],
                    ],
                ],
            ]);
        }
    }
}
