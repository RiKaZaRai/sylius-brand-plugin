<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class RikaSyliusBrandExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        // Configuration des resources via l'Extension (correct)
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $this->registerResources('rika_sylius_brand', 'doctrine/orm', $config['resources'], $container);

        // Chargement des services uniquement (conforme)
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.xml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependDoctrineMigrations($container);
        
        // Configuration des resources via prepend (approche moderne)
        if ($container->hasExtension('sylius_resource')) {
            $container->prependExtensionConfig('sylius_resource', [
                'resources' => [
                    'rika_sylius_brand.brand' => [
                        'driver' => 'doctrine/orm',
                        'classes' => [
                            'model' => 'Rika\SyliusBrandPlugin\Entity\Brand',
                            'repository' => 'Rika\SyliusBrandPlugin\Repository\BrandRepository',
                            'factory' => 'Rika\SyliusBrandPlugin\Factory\BrandFactory',
                        ],
                        'translation' => [
                            'classes' => [
                                'model' => 'Rika\SyliusBrandPlugin\Entity\BrandTranslation',
                                'factory' => 'Sylius\Resource\Factory\Factory',
                            ],
                        ],
                    ],
                ],
            ]);
        }

        // Configuration des paramètres
        $container->setParameter('rika_sylius_brand.upload_dir', '%kernel.project_dir%/public/media/brands');
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
        return [
            'Sylius\Bundle\CoreBundle\Migrations',
        ];
    }
}