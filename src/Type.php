<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

/**
 * @internal
 */
interface Type
{
    /**
     * @psalm-mutation-free
     */
    public function toArray(): array;
}
