<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

final class RikaSyliusBrandExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        // Paramètres spécifiques (comme CmsPlugin fait avec ses templates)
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