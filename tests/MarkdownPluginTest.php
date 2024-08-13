<?php

declare(strict_types=1);

namespace DZunke\PanalyMarkdownReport\Test;

use DZunke\PanalyMarkdownReport\MarkdownPlugin;
use DZunke\PanalyMarkdownReport\MarkdownReport;
use Panaly\Configuration\ConfigurationFile;
use Panaly\Configuration\RuntimeConfiguration;
use PHPUnit\Framework\TestCase;

class MarkdownPluginTest extends TestCase
{
    public function testPluginIsFullyInitialized(): void
    {
        $configurationFile    = $this->createMock(ConfigurationFile::class);
        $runtimeConfiguration = $this->createMock(RuntimeConfiguration::class);

        $matcher = $this->exactly(1);

        $runtimeConfiguration->expects($matcher)
            ->method('addReporting')
            ->willReturnCallback(static function (object $metric) use ($matcher): void {
                match ($matcher->numberOfInvocations()) {
                    1 => self::assertInstanceOf(MarkdownReport::class, $metric),
                    default => self::fail('Too much is going on here!'),
                };
            });

        (new MarkdownPlugin())->initialize($configurationFile, $runtimeConfiguration, []);
    }
}
