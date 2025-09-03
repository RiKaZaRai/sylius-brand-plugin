<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin;

use Rika\SyliusBrandPlugin\DependencyInjection\Compiler\GridCompilerPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RikaSyliusBrandPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new GridCompilerPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
