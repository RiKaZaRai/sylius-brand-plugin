<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('rika_sylius_brand');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()
        ;

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
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
                                        ->scalarNode('model')->defaultValue('Rika\SyliusBrandPlugin\Entity\Brand')->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue('Rika\SyliusBrandPlugin\Entity\BrandInterface')->end()
                                        ->scalarNode('controller')->defaultValue('Sylius\Bundle\ResourceBundle\Controller\ResourceController')->end()
                                        ->scalarNode('repository')->defaultValue('Rika\SyliusBrandPlugin\Repository\BrandRepository')->end()
                                        ->scalarNode('factory')->defaultValue('Rika\SyliusBrandPlugin\Factory\BrandFactory')->end()
                                        ->scalarNode('form')->defaultValue('Rika\SyliusBrandPlugin\Form\Type\BrandType')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('translation')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->variableNode('options')->end()
                                        ->arrayNode('classes')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('model')->defaultValue('Rika\SyliusBrandPlugin\Entity\BrandTranslation')->cannotBeEmpty()->end()
                                                ->scalarNode('interface')->defaultValue('Rika\SyliusBrandPlugin\Entity\BrandTranslationInterface')->end()
                                                ->scalarNode('controller')->defaultValue('Sylius\Bundle\ResourceBundle\Controller\ResourceController')->end()
                                                ->scalarNode('repository')->defaultValue('Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository')->end()
                                                ->scalarNode('factory')->defaultValue('Sylius\Resource\Factory\Factory')->end()
                                                ->scalarNode('form')->defaultValue('Rika\SyliusBrandPlugin\Form\Type\BrandTranslationType')->cannotBeEmpty()->end()
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