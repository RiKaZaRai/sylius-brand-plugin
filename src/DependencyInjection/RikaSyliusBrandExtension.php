<?php
// src/DependencyInjection/RikaSyliusBrandExtension.php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class RikaSyliusBrandExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        
        // Charger les services
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        // Configuration des ressources directement dans l'extension
        $this->registerResources('rika_sylius_brand', 'doctrine/orm', [
            'brand' => [
                'classes' => [
                    'model' => 'Rika\SyliusBrandPlugin\Entity\Brand',
                    'interface' => 'Rika\SyliusBrandPlugin\Entity\BrandInterface',
                    'repository' => 'Rika\SyliusBrandPlugin\Repository\BrandRepository',
                    'form' => 'Rika\SyliusBrandPlugin\Form\Type\BrandType',
                ],
                'translation' => [
                    'classes' => [
                        'model' => 'Rika\SyliusBrandPlugin\Entity\BrandTranslation',
                        'interface' => 'Rika\SyliusBrandPlugin\Entity\BrandTranslationInterface',
                        'form' => 'Rika\SyliusBrandPlugin\Form\Type\BrandTranslationType',
                    ],
                ],
            ],
        ], $container);
    }

    public function prepend(ContainerBuilder $container): void
    {
        // Configurer Doctrine pour les mappings XML
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
