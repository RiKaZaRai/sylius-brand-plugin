<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

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

        // Configuration des ressources
        $this->registerResources('rika_sylius_brand', 'doctrine/orm', [
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
        ], $container);

        // Créer explicitement le contrôleur
        $this->createController($container);
    }

    private function createController(ContainerBuilder $container): void
    {
        $controllerDefinition = new Definition(ResourceController::class);
        $controllerDefinition->setArguments([
            new Reference('rika_sylius_brand.metadata.brand'),
            new Reference('sylius.resource_controller.request_configuration_factory'),
            new Reference('sylius.resource_controller.view_handler'),
            new Reference('rika_sylius_brand.repository.brand'),
            new Reference('rika_sylius_brand.factory.brand'),
            new Reference('sylius.resource_controller.new_resource_factory'),
            new Reference('rika_sylius_brand.manager.brand'),
            new Reference('sylius.resource_controller.single_resource_provider'),
            new Reference('sylius.resource_controller.resources_collection_provider'),
            new Reference('sylius.resource_controller.form_factory'),
            new Reference('sylius.resource_controller.redirect_handler'),
            new Reference('sylius.resource_controller.flash_helper'),
            new Reference('sylius.security.authorization_checker'),
            new Reference('sylius.resource_controller.event_dispatcher'),
            new Reference('sylius.resource_controller.state_machine'),
            new Reference('sylius.resource_controller.resource_update_handler'),
            new Reference('sylius.resource_controller.resource_delete_handler'),
        ]);
        $controllerDefinition->setPublic(true);
        $controllerDefinition->addTag('controller.service_arguments');

        $container->setDefinition('rika_sylius_brand.controller.brand', $controllerDefinition);
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

        // Configuration des grilles
        if ($container->hasExtension('sylius_grid')) {
            $container->prependExtensionConfig('sylius_grid', [
                'grids' => [
                    'rika_sylius_brand_admin_brand' => [
                        'driver' => [
                            'name' => 'doctrine/orm',
                            'options' => [
                                'class' => 'Rika\SyliusBrandPlugin\Entity\Brand',
                            ],
                        ],
                        'sorting' => [
                            'name' => 'asc',
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
                                'sortable' => true,
                            ],
                            'logo' => [
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
}