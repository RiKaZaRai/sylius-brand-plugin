<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RikaSyliusBrandPlugin extends Bundle implements PrependExtensionInterface
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }

    public function prepend(ContainerBuilder $container): void
    {
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

        // Configuration de la grille admin
        $container->prependExtensionConfig('sylius_grid', [
            'grids' => [
                'admin_brand' => [
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
                        'position' => 'asc',
                    ],
                    'fields' => [
                        'logo' => [
                            'type' => 'twig',
                            'label' => 'Logo',
                            'options' => [
                                'template' => '@RikaSyliusBrandPlugin/Admin/Brand/Grid/Field/logo.html.twig',
                            ],
                        ],
                        'code' => [
                            'type' => 'string',
                            'label' => 'Code',
                        ],
                        'name' => [
                            'type' => 'string',
                            'label' => 'Nom',
                            'path' => 'name',
                        ],
                        'enabled' => [
                            'type' => 'twig',
                            'label' => 'Activé',
                            'options' => [
                                'template' => '@SyliusUi/Grid/Field/enabled.html.twig',
                            ],
                        ],
                        'position' => [
                            'type' => 'string',
                            'label' => 'Position',
                        ],
                    ],
                    'filters' => [
                        'search' => [
                            'type' => 'string',
                            'label' => 'Recherche',
                            'options' => [
                                'fields' => ['translations.name', 'code'],
                            ],
                        ],
                        'enabled' => [
                            'type' => 'boolean',
                            'label' => 'Activé',
                        ],
                    ],
                    'actions' => [
                        'main' => [
                            'create' => [
                                'type' => 'create',
                                'label' => 'Ajouter une marque',
                            ],
                        ],
                        'item' => [
                            'update' => [
                                'type' => 'update',
                                'label' => 'Modifier',
                            ],
                            'delete' => [
                                'type' => 'delete',
                                'label' => 'Supprimer',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}