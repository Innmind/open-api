<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

use Innmind\TimeContinuum\Clock;
use Innmind\Validation\Constraint;

/**
 * You can rely on this interface but DO NOT implement it as new methods may be
 * added in the future.
 *
 * @template T
 * @psalm-immutable
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
     * @template U
     *
     * @param callable(T): U $map
     *
     * @return self<U>
     */
    public function map(callable $map): self;

    /**
     * @template U
     *
     * @param Constraint<T, U> $constraint
     *
     * @return self<U>
     */
    public function constrain(Constraint $constraint): self;

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
