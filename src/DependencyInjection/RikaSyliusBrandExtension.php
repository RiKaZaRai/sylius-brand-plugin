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
        $config = $this->getCurrentConfiguration($container);

        // Paramètre pour le répertoire d’upload
        $container->setParameter('rika_sylius_brand.upload_dir', '%kernel.project_dir%/public/media/brand');

        // Déclaration des ressources Sylius
        $this->registerResources('rika_sylius_brand', 'doctrine/orm', $config['resources'], $container);

        // ⚠️ Resources est à la racine du plugin
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../Resources/config'));
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        // Mapping Doctrine
        if ($container->hasExtension('doctrine')) {
            $container->prependExtensionConfig('doctrine', [
                'orm' => [
                    'mappings' => [
                        'RikaSyliusBrandPlugin' => [
                            'type' => 'xml',
                            // ⚠️ Resources est à la racine du plugin
                            'dir' => __DIR__ . '/../../Resources/config/doctrine',
                            'prefix' => 'Rika\SyliusBrandPlugin\Entity',
                            'is_bundle' => false,
                        ],
                    ],
                ],
            ]);
        }

        // Ressources Sylius
        if ($container->hasExtension('sylius_resource')) {
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

        // Grid Admin
        if ($container->hasExtension('sylius_grid')) {
            $container->prependExtensionConfig('sylius_grid', [
                'grids' => [
                    'admin_brand' => [
                        'driver' => [
                            'name' => 'doctrine/orm',
                            'options' => [
                                'class' => 'Rika\SyliusBrandPlugin\Entity\Brand',
                                'repository' => [
                                    'method' => 'createListQueryBuilder',
                                ],
                            ],
                        ],
                        'sorting' => ['position' => 'asc'],
                        'fields' => [
                            'logo' => [
                                'type' => 'twig',
                                'label' => 'rika_sylius_brand.ui.logo',
                                'sortable' => false,
                                'options' => [
                                    'template' => '@RikaSyliusBrandPlugin/Admin/Brand/Grid/Field/logo.html.twig',
                                ],
                            ],
                            'name' => [
                                'type' => 'twig',
                                'label' => 'sylius.ui.name',
                                'sortable' => 'translation.name',
                                'options' => [
                                    'template' => '@SyliusAdmin/shared/crud/grid/field/translatable_string.html.twig',
                                ],
                            ],
                            'slug' => [
                                'type' => 'twig',
                                'label' => 'sylius.ui.slug',
                                'sortable' => 'translation.slug',
                                'options' => [
                                    'template' => '@SyliusAdmin/shared/crud/grid/field/translatable_string.html.twig',
                                ],
                            ],
                            'position' => [
                                'type' => 'string',
                                'label' => 'sylius.ui.position',
                            ],
                            'enabled' => [
                                'type' => 'twig',
                                'label' => 'sylius.ui.enabled',
                                'options' => [
                                    'template' => '@SyliusAdmin/shared/crud/grid/field/enabled.html.twig',
                                ],
                            ],
                        ],
                        'filters' => [
                            'search' => [
                                'type' => 'string',
                                'label' => 'sylius.ui.search',
                                'options' => [
                                    'fields' => ['translation.name', 'translation.slug'],
                                ],
                            ],
                            'enabled' => [
                                'type' => 'boolean',
                                'label' => 'sylius.ui.enabled',
                            ],
                        ],
                        'actions' => [
                            'main' => [
                                'create' => ['type' => 'create'],
                            ],
                            'item' => [
                                'show' => ['type' => 'show'],
                                'update' => ['type' => 'update'],
                                'delete' => ['type' => 'delete'],
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

    public function getAlias(): string
    {
        return 'rika_sylius_brand';
    }
}
