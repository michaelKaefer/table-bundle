<?php

namespace MichaelKaefer\TableBundle\Tests;

use MichaelKaefer\Table\TableType\TableType;
use MichaelKaefer\TableBundle\MichaelKaeferTableBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class MichaelKaeferTableBundleTest extends TestCase
{
    public function testLoadExtension(): void
    {
        $container = $this->createContainerBuilder();
        $container->registerExtension(new MichaelKaeferTableBundle()->getContainerExtension());
        $container->loadFromExtension('michael_kaefer_table', []);
        $this->compileContainer($container);

        $this->assertArrayHasKey('table.table_type', $container->getDefinition(TableType::class)->getTags());
    }

    private function createContainerBuilder(): ContainerBuilder
    {
        return new ContainerBuilder(new ParameterBag([
            'kernel.cache_dir' => __DIR__,
            'kernel.build_dir' => __DIR__,
            'kernel.charset' => 'UTF-8',
            'kernel.debug' => true,
            'kernel.project_dir' => __DIR__,
            'kernel.environment' => 'test',
            'kernel.bundles' => ['MichaelKaeferTableBundle' => MichaelKaeferTableBundle::class],
        ]));
    }

    private function compileContainer(ContainerBuilder $container): void
    {
        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);
        $container->compile();
    }
}
