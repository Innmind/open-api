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
     * @return Type<?T>
     */
    public function nullable(): self;

    /**
     * @psalm-mutation-free
     */
    public function toArray(): array;
}
