<?php

declare(strict_types=1);

namespace DZunke\PanalyMarkdownReport\Test;

use DZunke\PanalyMarkdownReport\MarkdownReport;
use Panaly\Result\Group;
use Panaly\Result\Metric;
use Panaly\Result\Result;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function unlink;

class MarkdownReportTest extends TestCase
{
    public function testTheReportGenerationIsWorking(): void
    {
        $group = new Group('First Title');
        $group->addMetric(new Metric('A Metric', new Metric\Integer(1)));
        $group->addMetric(new Metric('Another Metric', new Metric\Integer(2000)));
        $group->addMetric(new Metric('Wow! A Metric', new Metric\Integer(12)));

        $result = new Result();
        $result->addGroup($group);

        $markdownReport = new MarkdownReport();
        $markdownReport->report($result, ['targetFile' => 'foo-bar.md']);

        self::assertFileExists('foo-bar.md');

        $markdownReportFile = file_get_contents('foo-bar.md');

        self::assertIsString($markdownReportFile);
        self::assertStringContainsString('# First Title', $markdownReportFile);
        self::assertStringContainsString('This report was generated at', $markdownReportFile);
        self::assertStringContainsString('| A Metric       | 1     |', $markdownReportFile);

        @unlink('foo-bar.md');
    }
}
