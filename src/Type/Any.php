<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\OpenAPI\Type;
use Innmind\TimeContinuum\Clock;
use Innmind\Validation\Constraint;
use Innmind\Immutable\Sequence;

/**
 * @internal
 * @psalm-immutable
 * @template T
 * @template U
 * @implements Type<T|U>
 */
final class Any implements Type
{
    /** @var Type<T> */
    private Type $a;
    /** @var Type<U> */
    private Type $b;

    /**
     * @param Type<T> $a
     * @param Type<U> $b
     */
    private function __construct(Type $a, Type $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    /**
     * @template A
     * @template B
     * @psalm-pure
     *
     * @param Type<A> $a
     * @param Type<B> $b
     *
     * @return self<A, B>
     */
    public static function of(Type $a, Type $b): self
    {
        return new self($a, $b);
    }

    public function nullable(): Type
    {
        return Nullable::of($this);
    }

    public function map(callable $map): Type
    {
        return Map::of($this, $map);
    }

    public function constrain(Constraint $constraint): Type
    {
        return Constrain::of($this, $constraint);
    }

    public function or(Type $type): self
    {
        return new self($this, $type);
    }

    public function constraint(Clock $clock): Constraint
    {
        return $this->a->constraint($clock)->or(
            $this->b->constraint($clock),
        );
    }

    public function toArray(): array
    {
        $types = $this
            ->types()
            ->flatMap(static fn($type) => match (true) {
                $type instanceof self => $type->types(),
                default => Sequence::of($type),
            })
            ->map(static fn($type) => $type->toArray())
            ->toList();

        return [
            'anyOf' => $types,
        ];
    }

    /**
     * @return Sequence<Type>
     */
    private function types(): Sequence
    {
        return Sequence::of($this->a, $this->b);
    }
}
