<?php

declare(strict_types=1);

namespace DZunke\PanalyMarkdownReport\MarkdownDocument;

use DZunke\PanalyMarkdownReport\MarkdownDocument;

class Alert
{
    public function __construct(
        private readonly MarkdownDocument $document,
        private string $type = 'NOTE',
        private string $message = '',
    ) {
    }

    public function message(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function note(): self
    {
        $this->type = 'NOTE';

        return $this;
    }

    public function tip(): self
    {
        $this->type = 'TIP';

        return $this;
    }

    public function important(): self
    {
        $this->type = 'IMPORTANT';

        return $this;
    }

    public function warning(): self
    {
        $this->type = 'WARNING';

        return $this;
    }

    public function caution(): self
    {
        $this->type = 'CAUTION';

        return $this;
    }

    public function end(): MarkdownDocument
    {
        return $this->document
            ->writeLine('> [!' . $this->type . ']')
            ->writeLine('> ' . $this->message);
    }
}
