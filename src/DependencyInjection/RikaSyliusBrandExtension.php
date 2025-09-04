<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class RikaSyliusBrandExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        /** @var ConfigurationInterface $configuration */
        $configuration = $this->getConfiguration([], $container);
        $configs = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.xml');

        // Charger uniquement la grille directement
        $yamlLoader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config/grids'));
        $yamlLoader->load('admin.yaml');

        $container->setParameter('rika_sylius_brand.upload_dir', '%kernel.project_dir%/public/media/brands');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $config = $this->getCurrentConfiguration($container);
        
        // Configuration Doctrine
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

        // ✅ Configuration des Twig Hooks avec prepend (BONNES PRATIQUES Sylius 2.1)
        if ($container->hasExtension('sylius_twig_hooks')) {
            $this->prependTwigHooksConfig($container);
        }
        
        $this->registerResources('rika_sylius_brand', 'doctrine/orm', $config['resources'], $container);
        $this->prependDoctrineMigrations($container);
    }

    /**
     * Charge les Twig Hooks via prepend - Méthode recommandée Sylius 2.1
     */
    private function prependTwigHooksConfig(ContainerBuilder $container): void
    {
        // Charge tous les fichiers twig_hooks du plugin
        $twigHooksPath = __DIR__ . '/../../config/twig_hooks';
        
        if (is_dir($twigHooksPath)) {
            $finder = new \Symfony\Component\Finder\Finder();
            $finder->files()->name('*.yaml')->in($twigHooksPath);
            
            $yamlLoader = new YamlFileLoader($container, new FileLocator($twigHooksPath));
            
            foreach ($finder as $file) {
                $config = $yamlLoader->load($file->getRelativePathname());
                
                // Prepend chaque configuration de hook
                if (isset($config['sylius_twig_hooks'])) {
                    $container->prependExtensionConfig('sylius_twig_hooks', $config['sylius_twig_hooks']);
                }
            }
        }
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

    private function getCurrentConfiguration(ContainerBuilder $container): array
    {
        /** @var ConfigurationInterface $configuration */
        $configuration = $this->getConfiguration([], $container);
        $configs = $container->getExtensionConfig($this->getAlias());
        
        return $this->processConfiguration($configuration, $configs);
    }
}