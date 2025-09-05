<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class RikaSyliusBrandExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        // Chargement des services YAML - C'EST LA PARTIE MANQUANTE !
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');
        $loader->load('config.yaml');
        
        // Paramètres spécifiques
        $container->setParameter('rika_sylius_brand.upload_dir', '%kernel.project_dir%/public/media/brands');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $config = $this->getCurrentConfiguration($container);
        
        // Configuration Doctrine automatique
        if ($container->hasExtension('doctrine')) {
            $container->prependExtensionConfig('doctrine', [
                'orm' => [
                    'mappings' => [
                        'RikaSyliusBrandPlugin' => [
                            'type' => 'xml',
                            'dir' => __DIR__ . '/../../config/doctrine',
                            'prefix' => 'Rika\SyliusBrandPlugin\Entity',
                            'is_bundle' => false,
                        ],
                    ],
                ],
            ]);
        }
        
        $this->registerResources('rika_sylius_brand', 'doctrine/orm', $config['resources'], $container);
        $this->prependDoctrineMigrations($container);
    }

    // ... reste du code identique
    protected function getMigrationsNamespace(): string
    {
        return 'RikaSyliusBrandPlugin\Migrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@RikaSyliusBrandPlugin/src/Migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return ['Sylius\Bundle\CoreBundle\Migrations'];
    }

    private function getCurrentConfiguration(ContainerBuilder $container): array
    {
        /** @var ConfigurationInterface $configuration */
        $configuration = $this->getConfiguration([], $container);
        $configs = $container->getExtensionConfig($this->getAlias());
        
        return $this->processConfiguration($configuration, $configs);
    }
}