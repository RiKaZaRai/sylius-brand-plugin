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

        // Enregistrer les ressources
        $this->registerResources('rika_sylius_brand', 'doctrine/orm', $config['resources'], $container);

        // Charger les services
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        
        // Charger les grids seulement s'ils existent
        if (file_exists(__DIR__ . '/../Resources/config/grids.yaml')) {
            $loader->load('grids.yaml');
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

        // Configuration des ressources Sylius
        if ($container->hasExtension('sylius_resource')) {
            $container->prependExtensionConfig('sylius_resource', [
                'resources' => [
                    'rika_sylius_brand.brand' => [
                        'driver' => 'doctrine/orm',
                        'classes' => [
                            'model' => 'Rika\SyliusBrandPlugin\Entity\Brand',
                            'interface' => 'Rika\SyliusBrandPlugin\Entity\BrandInterface',
                            'repository' => 'Rika\SyliusBrandPlugin\Repository\BrandRepository',
                            'factory' => 'Rika\SyliusBrandPlugin\Factory\BrandFactory',
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
                ],
            ]);
        }
    }

    public function getConfiguration(array $config, ContainerBuilder $container): Configuration
    {
        return new Configuration();
    }
}
