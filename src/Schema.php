<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

/**
 * @psalm-immutable
 */
interface Schema extends \UnitEnum
{
    /**
     * @return non-empty-string
     */
    public function name(): string;
    public function type(): Type;
}
