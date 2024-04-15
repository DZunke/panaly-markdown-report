<?php

declare(strict_types=1);

namespace DZunke\PanalyMarkdownReport;

use Panaly\Plugin\BasePlugin;

final class MarkdownPlugin extends BasePlugin
{
    /** @inheritDoc */
    public function getAvailableReporting(array $options): array
    {
        return [new MarkdownReport()];
    }
}
