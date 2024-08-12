<?php

declare(strict_types=1);

namespace DZunke\PanalyMarkdownReport\Test;

use DZunke\PanalyMarkdownReport\MarkdownReport;
use DZunke\PanalyMarkdownReport\MarkdownReport\Exception\InvalidOptions;
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
        $group = new Group('foo', 'First Title');
        $group->addMetric(new Metric('foo', 'A Metric', new Metric\IntegerValue(1)));
        $group->addMetric(new Metric('bar', 'Another Metric', new Metric\IntegerValue(2000)));
        $group->addMetric(new Metric('baz', 'Wow! A Metric', new Metric\IntegerValue(12)));

        $group2 = new Group('foo', 'Second Title');
        $group2->addMetric(new Metric('foo', 'A table metric', new Metric\Table(['foo'], [['bar'], ['baz']])));

        $result = new Result();
        $result->addGroup($group);
        $result->addGroup($group2);

        $markdownReport = new MarkdownReport();
        $markdownReport->report($result, ['targetFile' => 'foo-bar.md']);

        self::assertFileExists('foo-bar.md');

        $markdownReportFile = file_get_contents('foo-bar.md');

        self::assertIsString($markdownReportFile);
        self::assertStringContainsString('# First Title', $markdownReportFile);
        self::assertStringContainsString('This report was generated at', $markdownReportFile);

        // Summarizes Scalar Values Table
        self::assertStringContainsString('### Summarized Scalar Values', $markdownReportFile);
        self::assertStringContainsString('| A Metric       | 1     |', $markdownReportFile);

        // Assertions for the table
        self::assertStringContainsString('### A table metric', $markdownReportFile);
        self::assertStringContainsString("| foo |\n|-----|\n| bar |\n| baz |", $markdownReportFile);

        @unlink('foo-bar.md');
    }

    public function testEmptyGroups(): void
    {
        $result = new Result();

        $markdownReport = new MarkdownReport();
        $markdownReport->report($result, ['targetFile' => 'empty-groups.md']);

        self::assertFileExists('empty-groups.md');

        $markdownReportFile = file_get_contents('empty-groups.md');

        self::assertIsString($markdownReportFile);
        self::assertStringContainsString('This report was generated at', $markdownReportFile);

        @unlink('empty-groups.md');
    }

    public function testInvalidOptions(): void
    {
        $this->expectException(InvalidOptions::class);

        $result         = new Result();
        $markdownReport = new MarkdownReport();
        $markdownReport->report($result, ['targetFile' => '/invalid/path/foo-bar.md']);
    }

    public function testFormatting(): void
    {
        $group = new Group('foo', 'Formatting Test');
        $group->addMetric(new Metric('foo', 'A Metric', new Metric\IntegerValue(1)));

        $result = new Result();
        $result->addGroup($group);

        $markdownReport = new MarkdownReport();
        $markdownReport->report($result, ['targetFile' => 'formatting-test.md']);

        self::assertFileExists('formatting-test.md');

        $markdownReportFile = file_get_contents('formatting-test.md');

        self::assertIsString($markdownReportFile);
        self::assertStringContainsString('# Formatting Test', $markdownReportFile);
        self::assertStringContainsString('| A Metric | 1     |', $markdownReportFile);

        @unlink('formatting-test.md');
    }
}
