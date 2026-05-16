<?php

namespace MichaelKaefer\TableBundle;

use MichaelKaefer\Table\Attribute\AsTableType;
use MichaelKaefer\Table\ColumnTypeExtensionInterface;
use MichaelKaefer\Table\ColumnTypeInterface;
use MichaelKaefer\Table\DependencyInjection\Compiler\AsTableTypePass;
use MichaelKaefer\Table\TableRegistry;
use MichaelKaefer\Table\TableTypeExtensionInterface;
use MichaelKaefer\Table\TableTypeInterface;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class MichaelKaeferTableBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('themes')
                    ->scalarPrototype()->end()
                    ->defaultValue(['@MichaelKaeferTable/table.html.twig'])
                ->end()
            ->end()
        ;
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new AsTableTypePass());
    }

    public function loadExtension(array $config, ContainerConfigurator $configurator, ContainerBuilder $container): void
    {
        $container->setParameter('table.themes', $config['themes']);

        $container->registerAttributeForAutoconfiguration(AsTableType::class, static function (ChildDefinition $definition) {
            $definition->addResourceTag('table.as_table_type');
        });

        $container->registerForAutoconfiguration(TableTypeInterface::class)->addTag('table.table_type');

        $container->registerForAutoconfiguration(TableTypeExtensionInterface::class)->addTag('table.table_type_extension');

        $container->registerForAutoconfiguration(ColumnTypeInterface::class)->addTag('table.column_type');

        $container->registerForAutoconfiguration(ColumnTypeExtensionInterface::class)->addTag('table.column_type_extension');

        $configurator->import('../config/services.php');
    }

    public function prependExtension(ContainerConfigurator $configurator, ContainerBuilder $container): void
    {
        $path = \dirname(new \ReflectionClass(TableRegistry::class)->getFileName()).'/../templates';

        $container->prependExtensionConfig('twig', ['paths' => [$path => 'MichaelKaeferTable']]);
    }
}
