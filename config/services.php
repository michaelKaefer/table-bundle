<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use MichaelKaefer\Table\Accessor;
use MichaelKaefer\Table\AccessorInterface;
use MichaelKaefer\Table\CellViewFactory;
use MichaelKaefer\Table\ColumnType;
use MichaelKaefer\Table\Renderer\TwigRenderer;
use MichaelKaefer\Table\RendererInterface;
use MichaelKaefer\Table\TableBuilderFactory;
use MichaelKaefer\Table\TableFactory;
use MichaelKaefer\Table\TableRegistry;
use MichaelKaefer\Table\TableType;
use MichaelKaefer\Table\TableViewFactory;
use MichaelKaefer\Table\Twig\TableExtension;
use Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;

return static function (ContainerConfigurator $container) {
    $services = $container->services()
        ->defaults()
        ->private()
    ;

    $services
        ->set(TableType\TableType::class)
            ->autowire()
            ->tag('table.table_type')

        ->set(ColumnType\ColumnType::class)
            ->tag('table.column_type')

        ->set(ColumnType\BooleanType::class)
            ->tag('table.column_type')

        ->set(ColumnType\CollectionType::class)
            ->autowire()
            ->tag('table.column_type')

        ->set(ColumnType\DateTimeType::class)
            ->tag('table.column_type')

        ->set(ColumnType\TableType::class)
            ->autowire()
            ->tag('table.column_type')

        ->set(ColumnType\TextType::class)
            ->tag('table.column_type')

        ->set(Accessor\PropertyPathAccessor::class)
            ->tag('table.accessor')

        ->set(Accessor\CallbackAccessor::class)
            ->tag('table.accessor')

        ->set(TableRegistry::class)
            ->args([
                tagged_locator('table.table_type', 'key'),
                new ServiceLocatorArgument(new TaggedIteratorArgument('table.table_type_extension')),
                tagged_locator('table.column_type'),
                new ServiceLocatorArgument(new TaggedIteratorArgument('table.column_type_extension')),
            ])

        ->set(TableBuilderFactory::class)
            ->autowire()

        ->set(TableFactory::class)
            ->autowire()

        ->set(Accessor\ChainAccessor::class)
            ->args([
                new ServiceLocatorArgument(new TaggedIteratorArgument('table.accessor')),
            ])

        ->alias(AccessorInterface::class, Accessor\ChainAccessor::class)

        ->set(CellViewFactory::class)
            ->autowire()

        ->set(TableViewFactory::class)
            ->autowire()

        ->set(TwigRenderer::class)
            ->args([service('twig'), param('table.themes')])

        ->alias(RendererInterface::class, TwigRenderer::class)

        ->set('table.twig.extension', TableExtension::class)
            ->autowire()
            ->tag('twig.extension')
    ;
};
