<?php

declare(strict_types=1);

namespace DZunke\PanalyMarkdownReport\MarkdownReport\Exception;

use InvalidArgumentException;

final class InvalidOptions extends InvalidArgumentException
{
    public static function targetPathIsNotWritable(string $targetPath): InvalidOptions
    {
        return new self('The target path "' . $targetPath . '" is not writable.');
    }
}
