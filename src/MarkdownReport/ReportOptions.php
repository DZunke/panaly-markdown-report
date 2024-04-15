<?php

declare(strict_types=1);

namespace DZunke\PanalyMarkdownReport\MarkdownReport;

use DZunke\PanalyMarkdownReport\MarkdownReport\Exception\InvalidOptions;

use function dirname;
use function is_writable;

use const DIRECTORY_SEPARATOR;

readonly class ReportOptions
{
    public function __construct(
        public string $targetFile,
    ) {
        if (! is_writable(dirname($this->targetFile))) {
            throw InvalidOptions::targetPathIsNotWritable(dirname($this->targetFile));
        }
    }

    public static function fromArray(array $options): ReportOptions
    {
        return new self(
            $options['targetFile'] ?? dirname('.') . DIRECTORY_SEPARATOR . 'my-markdown-report.md',
        );
    }
}
