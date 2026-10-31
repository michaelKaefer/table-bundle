<?php

namespace MichaelKaefer\TableBundle\Tests;

use MichaelKaefer\Table\TableType\TableType;
use MichaelKaefer\TableBundle\MichaelKaeferTableBundle;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\DebugBundle\DependencyInjection\Compiler\DumpDataCollectorPass;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\DataCollector\DumpDataCollector;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\VarDumper\Caster\ReflectionCaster;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Server\Connection;
use Symfony\Component\VarDumper\Server\DumpServer;

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

    //    public function testUnsetClosureFileInfoShouldBeRegisteredInVarCloner()
    //    {
    //        $container = $this->createContainerBuilder();
    //        $container->registerExtension(new DebugBundle()->getContainerExtension());
    //        $container->loadFromExtension('debug', []);
    //        $this->compileContainer($container);
    //
    //        $definition = $container->getDefinition('var_dumper.cloner');
    //
    //        $called = false;
    //        foreach ($definition->getMethodCalls() as $call) {
    //            if ('addCasters' !== $call[0]) {
    //                continue;
    //            }
    //
    //            $argument = $call[1][0] ?? null;
    //            if (null === $argument) {
    //                continue;
    //            }
    //
    //            if (['Closure' => ReflectionCaster::class.'::unsetClosureFileInfo'] === $argument) {
    //                $called = true;
    //                break;
    //            }
    //        }
    //
    //        $this->assertTrue($called);
    //    }
    //
    //    public static function provideServicesUsingDumpDestinationCreation(): array
    //    {
    //        return [
    //            ['tcp://localhost:1234', 'tcp://localhost:1234', null],
    //            [null, '', null],
    //            ['php://stderr', '', 'php://stderr'],
    //        ];
    //    }
    //
    //    #[DataProvider('provideServicesUsingDumpDestinationCreation')]
    //    public function testServicesUsingDumpDestinationCreation(?string $dumpDestination, string $expectedHost, ?string $expectedOutput)
    //    {
    //        $container = $this->createContainerBuilder();
    //        $container->registerExtension(new DebugBundle()->getContainerExtension());
    //        $container->loadFromExtension('debug', ['dump_destination' => $dumpDestination]);
    //        $container->setAlias('dump_server_public', 'var_dumper.dump_server')->setPublic(true);
    //        $container->setAlias('server_conn_public', 'var_dumper.server_connection')->setPublic(true);
    //        $container->setAlias('cli_dumper_public', 'var_dumper.cli_dumper')->setPublic(true);
    //        $container->register('request_stack', RequestStack::class);
    //        $this->compileContainer($container);
    //
    //        $dumpServer = $container->get('dump_server_public');
    //        $this->assertInstanceOf(DumpServer::class, $dumpServer);
    //        $this->assertSame($expectedHost, $container->findDefinition('dump_server_public')->getArgument(0));
    //
    //        $serverConn = $container->get('server_conn_public');
    //        $this->assertInstanceOf(Connection::class, $serverConn);
    //        $this->assertSame($expectedHost, $container->findDefinition('server_conn_public')->getArgument(0));
    //
    //        $cliDumper = $container->get('cli_dumper_public');
    //        $this->assertInstanceOf(CliDumper::class, $cliDumper);
    //        $this->assertSame($expectedOutput, $container->findDefinition('cli_dumper_public')->getArgument(0));
    //    }
    //
    //    public function testRuntimeModeIsNotSetIfParameterDoesNotExist()
    //    {
    //        $kernel = new class('dev', true) extends Kernel {
    //            public function registerBundles(): iterable
    //            {
    //                return [];
    //            }
    //
    //            public function registerContainerConfiguration(LoaderInterface $loader): void
    //            {
    //            }
    //
    //            public function getKernelParameters(): array
    //            {
    //                return parent::getKernelParameters();
    //            }
    //        };
    //        $container = new ContainerBuilder();
    //        $container->getParameterBag()->add($kernel->getKernelParameters());
    //        $container->registerExtension(new DebugBundle()->getContainerExtension());
    //        $container->loadFromExtension('debug', []);
    //        $container->addCompilerPass(new DumpDataCollectorPass());
    //        $this->compileContainer($container);
    //
    //        $this->assertInstanceOf(DumpDataCollector::class, $container->get('data_collector.dump'));
    //    }

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
