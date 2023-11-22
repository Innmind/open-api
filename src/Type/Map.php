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
final class Map implements Type
{
    /** @var Type<T> */
    private Type $type;
    /** @var callable(T): U */
    private $map;

    /**
     * @param Type<T> $type
     * @param callable(T): U $map
     */
    private function __construct(Type $type, callable $map)
    {
        $this->type = $type;
        $this->map = $map;
    }

    /**
     * @template A
     * @template B
     * @psalm-pure
     *
     * @param Type<A> $type
     * @param callable(A): B $map
     *
     * @return self<A, B>
     */
    public static function of(Type $type, callable $map): self
    {
        return new self($type, $map);
    }

    public function nullable(): Type
    {
        return Nullable::of($this);
    }

    public function map(callable $map): self
    {
        return new self($this, $map);
    }

    public function constraint(Clock $clock): Constraint
    {
        /** @psalm-suppress ArgumentTypeCoercion Don't know why it loses the type */
        return $this
            ->type
            ->constraint($clock)
            ->map($this->map);
    }

    public function toArray(): array
    {
        return $this->type->toArray();
    }
}
