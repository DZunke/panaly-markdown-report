<?php

declare(strict_types=1);

namespace DZunke\PanalyMarkdownReport;

use DZunke\PanalyMarkdownReport\MarkdownDocument\Alert;
use DZunke\PanalyMarkdownReport\MarkdownDocument\Table;
use Stringable;

use function rtrim;
use function str_repeat;

use const PHP_EOL;

class MarkdownDocument implements Stringable
{
    private string $markdown = '';

    public function __toString(): string
    {
        return rtrim($this->markdown, PHP_EOL);
    }

    public function addLines(int $amount = 1): self
    {
        $this->markdown .= str_repeat(PHP_EOL, $amount);

        return $this;
    }

    public function writeLine(string $line): self
    {
        $this->markdown .= $line;
        $this->addLines(1);

        return $this;
    }

    public function heading(string $heading, int $level = 1): self
    {
        return $this->writeLine(str_repeat('#', $level) . ' ' . $heading);
    }

    public function title(string $title): self
    {
        return $this->heading($title);
    }

    public function alert(): Alert
    {
        return new Alert($this);
    }

    public function table(): Table
    {
        return new Table($this);
    }
}
