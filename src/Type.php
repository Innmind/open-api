<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

use Innmind\TimeContinuum\Clock;
use Innmind\Validation\Constraint;

/**
 * @internal
 * @template T
 */
interface Type
{
    /**
     * @psalm-mutation-free
     *
     * @return Type<?T>
     */
    public function nullable(): self;

    /**
     * @psalm-mutation-free
     *
     * @return Constraint<mixed, T>
     */
    public function constraint(Clock $clock): Constraint;

    /**
     * @psalm-mutation-free
     */
    public function toArray(): array;
}
