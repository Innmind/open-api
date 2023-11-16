<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

/**
 * @internal
 * @template T
 */
interface Type
{
    /**
     * @psalm-mutation-free
     */
    public function toArray(): array;
}
