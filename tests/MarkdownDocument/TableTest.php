<?php

declare(strict_types=1);

namespace DZunke\PanalyMarkdownReport\Test\MarkdownDocument;

use DZunke\PanalyMarkdownReport\MarkdownDocument;
use DZunke\PanalyMarkdownReport\MarkdownDocument\Table;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class TableTest extends TestCase
{
    private MarkdownDocument&MockObject $document;

    protected function setUp(): void
    {
        $this->document = $this->createMock(MarkdownDocument::class);
    }

    protected function tearDown(): void
    {
        unset($this->document);
    }

    public function testColumns(): void
    {
        $table = new Table($this->document);
        $table->columns(['Column1', 'Column2']);

        self::assertSame(
            ['Column1', 'Column2'],
            $this->getPrivateProperty($table, 'columns'),
        );
    }

    public function testRows(): void
    {
        $table = new Table($this->document);
        $table->rows([['Row1Col1', 'Row1Col2'], ['Row2Col1', 'Row2Col2']]);

        self::assertSame(
            [['Row1Col1', 'Row1Col2'], ['Row2Col1', 'Row2Col2']],
            $this->getPrivateProperty($table, 'rows'),
        );
    }

    public function testEnd(): void
    {
        $this->document->expects($this->exactly(4))->method('writeLine');
        $this->document->expects($this->exactly(2))->method('addLines');

        $table = new Table($this->document);
        $table->columns(['Col1', 'Col2']);
        $table->rows([['Data1', 'Data2'], ['Data3', 'Data4']]);

        $table->end();
    }

    public function testEndWithEmptyColumnsAndRows(): void
    {
        $this->document->expects($this->never())->method('writeLine');
        $this->document->expects($this->never())->method('addLines');

        $table = new Table($this->document);
        $table->end();
    }

    public function testEndWithColumnsButEmptyRows(): void
    {
        $this->document->expects($this->exactly(2))->method('writeLine');
        $this->document->expects($this->exactly(2))->method('addLines');

        $table = new Table($this->document);
        $table->columns(['Col1', 'Col2']);

        $table->end();
    }

    public function testGetColumnLengths(): void
    {
        $table = new Table($this->document);
        $table->columns(['Col1', 'Col2']);
        $table->rows([['Data1', 'Data2'], ['Data3', 'Data4']]);

        $method = new ReflectionMethod(Table::class, 'getColumnLengths');

        $columnLengths = $method->invoke($table);

        self::assertSame([5, 5], $columnLengths);
    }

    public function testEndWithRowsButEmptyColumns(): void
    {
        $this->document->expects($this->never())->method('writeLine');
        $this->document->expects($this->never())->method('addLines');

        $table = new Table($this->document);
        $table->rows([['Data1', 'Data2']]);

        $table->end();
    }

    public function testGetColumnLengthsWithColumnsButEmptyRows(): void
    {
        $table = new Table($this->document);
        $table->columns(['Col1', 'Col2']);

        $method = new ReflectionMethod(Table::class, 'getColumnLengths');
        self::assertSame([4, 4], $method->invoke($table)); // Assuming minimum length of 3 and column names are longer
    }

    private function getPrivateProperty(object $object, string $property): mixed
    {
        $reflection = new ReflectionClass($object);
        $property   = $reflection->getProperty($property);

        return $property->getValue($object);
    }
}
