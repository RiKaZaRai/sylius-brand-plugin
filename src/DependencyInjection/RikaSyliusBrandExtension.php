<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class RikaSyliusBrandExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');
        
        // Paramètres de configuration
        $container->setParameter('rika_sylius_brand.upload_dir', $config['upload_dir']);
        $container->setParameter('rika_sylius_brand.features.enable_brand_filtering', $config['features']['enable_brand_filtering']);
        $container->setParameter('rika_sylius_brand.features.enable_brand_pages', $config['features']['enable_brand_pages']);
        $container->setParameter('rika_sylius_brand.features.enable_brand_logos', $config['features']['enable_brand_logos']);
    }

    public function prepend(ContainerBuilder $container): void
    {
        $config = $this->getCurrentConfiguration($container);
        
        // Enregistrer les ressources Sylius
        $this->registerResources('rika_sylius_brand', 'doctrine/orm', $config['resources'], $container);
        
        $this->prependDoctrineMigrations($container);
    }

    private function getCurrentConfiguration(ContainerBuilder $container): array
    {
        $configuration = $this->getConfiguration([], $container);
        $configs = $container->getExtensionConfig($this->getAlias());
        return $this->processConfiguration($configuration, $configs);
    }

    protected function getMigrationsNamespace(): string
    {
        return 'Rika\SyliusBrandPlugin\Migrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@RikaSyliusBrandPlugin/src/Migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return [
            'Sylius\Bundle\CoreBundle\Migrations',
        ];
    }
}