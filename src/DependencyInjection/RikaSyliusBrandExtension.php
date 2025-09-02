<?php

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
        
        // Définir le paramètre pour le répertoire d'upload
        $container->setParameter('rika_sylius_brand.upload_dir', '%kernel.project_dir%/public/media/brand');
        
        // Charger les fichiers de configuration
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        $loader->load('sylius_resource.yaml');
        
        // Charger les grilles si elles existent
        if (file_exists(__DIR__ . '/../Resources/config/grids/admin_brand.yaml')) {
            $loader->load('grids/admin_brand.yaml');
        }
        if (file_exists(__DIR__ . '/../Resources/config/sylius_grid.yaml')) {
            $loader->load('sylius_grid.yaml');
        }
    }

    public function prepend(ContainerBuilder $container): void
    {
        // Configuration Doctrine
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