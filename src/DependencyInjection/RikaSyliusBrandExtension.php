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
        
        // Charger les services
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $config = $this->getCurrentConfiguration($container);
        
        // Enregistrer les ressources DANS prepend() comme le plugin officiel
        $this->registerResources('rika_sylius_brand', 'doctrine/orm', $config['resources'], $container);
        
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

        // Configuration des grilles
        if ($container->hasExtension('sylius_grid')) {
            $container->prependExtensionConfig('sylius_grid', [
                'grids' => [
                    'rika_sylius_brand_admin_brand' => [
                        'driver' => [
                            'name' => 'doctrine/orm',
                            'options' => [
                                'class' => 'Rika\SyliusBrandPlugin\Entity\Brand',
                                'repository' => [
                                    'method' => 'createListQueryBuilder',
                                    'arguments' => ['%locale%'],
                                ],
                            ],
                        ],
                        'sorting' => [
                            'code' => 'asc', // Tri par code au lieu de name
                        ],
                        'fields' => [
                            'code' => [
                                'type' => 'string',
                                'label' => 'sylius.ui.code',
                                'sortable' => true,
                            ],
                            'name' => [
                                'type' => 'string',
                                'label' => 'sylius.ui.name',
                                'path' => 'translation.name',
                                'sortable' => 'translation.name',
                            ],
                            'logoPath' => [
                                'type' => 'twig',
                                'label' => 'rika_sylius_brand.ui.logo',
                                'options' => [
                                    'template' => '@RikaSyliusBrandPlugin/Admin/Brand/Grid/Field/logo.html.twig',
                                ],
                            ],
                            'enabled' => [
                                'type' => 'twig',
                                'label' => 'sylius.ui.enabled',
                                'options' => [
                                    'template' => '@SyliusUi/Grid/Field/enabled.html.twig',
                                ],
                            ],
                            'position' => [
                                'type' => 'string',
                                'label' => 'sylius.ui.position',
                                'sortable' => true,
                            ],
                        ],
                        'filters' => [
                            'search' => [
                                'type' => 'string',
                                'label' => 'sylius.ui.search',
                                'options' => [
                                    'fields' => ['code', 'translation.name'],
                                ],
                            ],
                            'enabled' => [
                                'type' => 'boolean',
                                'label' => 'sylius.ui.enabled',
                            ],
                        ],
                        'actions' => [
                            'main' => [
                                'create' => [
                                    'type' => 'create',
                                ],
                            ],
                            'item' => [
                                'update' => [
                                    'type' => 'update',
                                ],
                                'delete' => [
                                    'type' => 'delete',
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
        }
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