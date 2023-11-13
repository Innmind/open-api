<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

/**
 * @psalm-immutable
 */
interface Tag extends \UnitEnum
{
    /**
     * @return non-empty-string
     */
    public function name(): string;
    public function description(): ?string;
}
