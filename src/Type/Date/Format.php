<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type\Date;

use Innmind\TimeContinuum\Format as FormatInterface;

/**
 * @psalm-immutable
 */
final class Format implements FormatInterface
{
    public function toString(): string
    {
        return '!Y-m-d';
    }
}
