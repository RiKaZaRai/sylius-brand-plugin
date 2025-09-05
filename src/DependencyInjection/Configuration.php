<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\DependencyInjection;

use Rika\SyliusBrandPlugin\Entity\Brand;
use Rika\SyliusBrandPlugin\Entity\BrandTranslation;
use Rika\SyliusBrandPlugin\Factory\BrandFactory;
use Rika\SyliusBrandPlugin\Form\Type\BrandTranslationType;
use Rika\SyliusBrandPlugin\Form\Type\BrandType;
use Rika\SyliusBrandPlugin\Repository\BrandRepository;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('rika_sylius_brand');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('upload_dir')
                    ->defaultValue('%kernel.project_dir%/public/media/brands')
                    ->info('Directory where brand logos and images are stored')
                ->end()
                ->arrayNode('features')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable_brand_filtering')
                            ->defaultTrue()
                            ->info('Enable brand filtering on product listings')
                        ->end()
                        ->booleanNode('enable_brand_pages')
                            ->defaultTrue()
                            ->info('Enable individual brand pages on shop')
                        ->end()
                        ->booleanNode('enable_brand_logos')
                            ->defaultTrue()
                            ->info('Enable brand logo upload and display')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    private function addResourcesSection($node): void
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('brand')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Brand::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(BrandRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(BrandFactory::class)->cannotBeEmpty()->end()
                                        ->scalarNode('form')->defaultValue(BrandType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('translation')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->variableNode('options')->end()
                                        ->arrayNode('classes')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('model')->defaultValue(BrandTranslation::class)->cannotBeEmpty()->end()
                                                ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                                ->scalarNode('repository')->defaultValue(EntityRepository::class)->cannotBeEmpty()->end()
                                                ->scalarNode('factory')->defaultValue(Factory::class)->cannotBeEmpty()->end()
                                                ->scalarNode('form')->defaultValue(BrandTranslationType::class)->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}