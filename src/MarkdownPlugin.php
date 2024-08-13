<?php

declare(strict_types=1);

namespace DZunke\PanalyMarkdownReport;

use Panaly\Configuration\ConfigurationFile;
use Panaly\Configuration\RuntimeConfiguration;
use Panaly\Plugin\Plugin;

final class MarkdownPlugin implements Plugin
{
    /** @inheritDoc */
    public function initialize(
        ConfigurationFile $configurationFile,
        RuntimeConfiguration $runtimeConfiguration,
        array $options,
    ): void {
        $runtimeConfiguration->addReporting(new MarkdownReport());
    }
}
