<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class GridCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('sylius_grid.grids')) {
            $container->setParameter('sylius_grid.grids', []);
        }

        $grids = $container->getParameter('sylius_grid.grids');
        
        $grids['admin_brand'] = [
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
        ];

        $container->setParameter('sylius_grid.grids', $grids);
    }
}