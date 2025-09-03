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
    }

    public function prepend(ContainerBuilder $container): void
    {
        // Configuration Doctrine uniquement
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
        $container->prependExtensionConfig('sylius_resource', [
            'resources' => [
                'rika_sylius_brand.brand' => [
                    'driver' => 'doctrine/orm',
                    'classes' => [
                        'model' => 'Rika\SyliusBrandPlugin\Entity\Brand',
                        'repository' => 'Rika\SyliusBrandPlugin\Repository\BrandRepository',
                        'factory' => 'Rika\SyliusBrandPlugin\Factory\BrandFactory',
                        'form' => 'Rika\SyliusBrandPlugin\Form\Type\BrandType',
                    ],
                    'translation' => [
                        'classes' => [
                            'model' => 'Rika\SyliusBrandPlugin\Entity\BrandTranslation',
                            'form' => 'Rika\SyliusBrandPlugin\Form\Type\BrandTranslationType',
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function getCurrentConfiguration(ContainerBuilder $container): array
    {
        $configuration = $this->getConfiguration([], $container);
        $configs = $container->getExtensionConfig($this->getAlias());
        
        // Configuration par défaut des ressources
        $defaultConfig = [
            'resources' => [
                'brand' => [
                    'classes' => [
                        'model' => 'Rika\SyliusBrandPlugin\Entity\Brand',
                        'interface' => 'Rika\SyliusBrandPlugin\Entity\BrandInterface',
                        'repository' => 'Rika\SyliusBrandPlugin\Repository\BrandRepository',
                        'form' => 'Rika\SyliusBrandPlugin\Form\Type\BrandType',
                        'factory' => 'Rika\SyliusBrandPlugin\Factory\BrandFactory',
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
        ];
        
        return $this->processConfiguration($configuration, array_merge([$defaultConfig], $configs));
    }
}