<?php

declare(strict_types=1);

namespace DZunke\PanalyMarkdownReport;

use DateTimeInterface;
use DZunke\PanalyMarkdownReport\MarkdownReport\ReportOptions;
use Panaly\Plugin\Plugin\Reporting;
use Panaly\Result\Group;
use Panaly\Result\Metric;
use Panaly\Result\Result;

use function file_put_contents;
use function is_scalar;

final readonly class MarkdownReport implements Reporting
{
    public function getIdentifier(): string
    {
        return 'markdown';
    }

    public function report(Result $result, array $options): void
    {
        $reportOptions = ReportOptions::fromArray($options);

        $createdAtDate    = $result->getCreateAt()->format(DateTimeInterface::ATOM);
        $markdownDocument = new MarkdownDocument();
        $markdownDocument
            ->title('Metric Report')
            ->addLines()
            ->alert()
            ->note()
            ->message('This report was generated at `' . $createdAtDate . '`')
            ->end()
            ->addLines(2);

        $this->renderGroups($markdownDocument, $result->getGroups());

        file_put_contents($reportOptions->targetFile, (string) $markdownDocument);
    }

    /** @param list<Group> $groups */
    private function renderGroups(MarkdownDocument $document, array $groups): void
    {
        if ($groups === []) {
            $document->alert()
                ->caution()
                ->message('There are no metric groups within the result, please check your configuration.')
                ->end();

            return;
        }

        foreach ($groups as $group) {
            $document->heading($group->getTitle());

            $this->renderMetrics($document, $group->getMetrics());
        }
    }

    /** @param list<Metric> $metrics */
    private function renderMetrics(MarkdownDocument $document, array $metrics): void
    {
        if ($metrics === []) {
            $document->alert()
                ->caution()
                ->message('There are no metrics within the result group, please check your configuration.')
                ->end();

            return;
        }

        /** @var list<array> $integerValues */
        $integerValues = []; // Collect the Integer values as they will be combined to a table
        foreach ($metrics as $metric) {
            if ($metric->value instanceof Metric\Integer) {
                $integerValues[] = [$metric->title, $metric->value->compute()];
                continue;
            }

            if ($metric->value instanceof Metric\Table) {
                $document->addLines();
                $document->heading($metric->title, 3);
                $document->table()->columns($metric->value->columns)->rows($metric->value->rows)->end();
            }

            $metricResult = $metric->value->compute();
            if (! is_scalar($metricResult)) {
                continue;
            }

            $document->writeLine($metric->title . ' - ' . $metric->value->compute())->addLines();
        }

        if ($integerValues === []) {
            return;
        }

        $document->table()->columns(['Metric', 'Value'])->rows($integerValues)->end();
    }
}
