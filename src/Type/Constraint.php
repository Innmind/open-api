<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\OpenAPI\Type;
use Innmind\TimeContinuum\Clock;
use Innmind\Validation\Constraint;

/**
 * @internal
 * @psalm-immutable
 * @template T
 * @template U
 * @implements Type<U>
 */
final class Constrain implements Type
{
    /** @var Type<T> */
    private Type $type;
    /** @var Constraint<T, U> */
    private Constraint $constraint;

    /**
     * @param Type<T> $type
     * @param Constraint<T, U> $constraint
     */
    private function __construct(Type $type, Constraint $constraint)
    {
        $this->type = $type;
        $this->constraint = $constraint;
    }

    /**
     * @template A
     * @template B
     * @psalm-pure
     *
     * @param Type<A> $type
     * @param Constraint<A, B> $constraint
     *
     * @return self<A, B>
     */
    public static function of(Type $type, Constraint $constraint): self
    {
        return new self($type, $constraint);
    }

    public function nullable(): Type
    {
        return Nullable::of($this);
    }

    public function map(callable $map): Type
    {
        return Map::of($this, $map);
    }

    public function constrain(Constraint $constraint): self
    {
        return new self($this, $constraint);
    }

    public function constraint(Clock $clock): Constraint
    {
        return $this
            ->type
            ->constraint($clock)
            ->and($this->constraint);
    }

    public function toArray(): array
    {
        return $this->type->toArray();
    }
}
