<?php

declare(strict_types=1);

namespace DZunke\PanalyMarkdownReport\MarkdownDocument;

use DZunke\PanalyMarkdownReport\MarkdownDocument;

use function array_fill;
use function array_map;
use function array_values;
use function count;
use function max;
use function str_pad;
use function strlen;

class Table
{
    public function __construct(
        private readonly MarkdownDocument $document,
        private array $columns = [],
        private array $rows = [],
    ) {
    }

    public function columns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function rows(array $rows): self
    {
        $this->rows = $rows;

        return $this;
    }

    public function end(): MarkdownDocument
    {
        $columnLengths = $this->getColumnLengths();

        $this->document->addLines();

        // Header
        $headerLine    = '';
        $delimeterLine = '';
        foreach ($this->columns as $index => $colum) {
            $headerLine    .= '| ' . str_pad($colum, $columnLengths[$index] + 1);
            $delimeterLine .= '|-' . str_pad('', $columnLengths[$index] + 1, '-');
        }

        $this->document->writeLine($headerLine . '|');
        $this->document->writeLine($delimeterLine . '|');

        foreach ($this->rows as $row) {
            $rowLine = '';
            for ($index = 0, $indexMax = count($columnLengths); $index < $indexMax; $index++) {
                $rowLine .= '| ' . str_pad((string) $row[$index], $columnLengths[$index] + 1);
            }

            $this->document->writeLine($rowLine . '|');
        }

        $this->document->addLines();

        return $this->document;
    }

    /** @return list<int> */
    private function getColumnLengths(): array
    {
        $columnCount   = count($this->columns);
        $columnLengths = array_fill(0, $columnCount, 3);

        for ($i = 0; $i < $columnCount; ++$i) {
            $headerLength = strlen($this->columns[$i]);
            if ($headerLength > 3) {
                $columnLengths[$i] = $headerLength;
            }

            $columValues = array_map(static fn (array $row) => $row[$i], array_values($this->rows));
            $columValues = array_map(static fn (string $value) => strlen($value), $columValues);

            $maxColumnLength = max($columValues);
            if ($maxColumnLength <= $columnLengths[$i]) {
                continue;
            }

            $columnLengths[$i] = $maxColumnLength;
        }

        return $columnLengths;
    }
}
