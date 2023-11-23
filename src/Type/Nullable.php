<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\OpenAPI\Type;
use Innmind\TimeContinuum\Clock;
use Innmind\Validation\{
    Constraint,
    Is,
};

/**
 * @psalm-immutable
 * @template T
 * @implements Type<?T>
 */
final class Nullable implements Type
{
    /** @var Type<T> */
    private Type $type;

    /**
     * @param Type<T> $type
     */
    private function __construct(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @template A
     * @psalm-pure
     *
     * @param Type<A> $type
     *
     * @return self<A>
     */
    public static function of(Type $type): self
    {
        return new self($type);
    }

    public function nullable(): self
    {
        return $this;
    }

    public function map(callable $map): Type
    {
        return Map::of($this, $map);
    }

    public function constrain(Constraint $constraint): Type
    {
        return Constrain::of($this, $constraint);
    }

    public function or(Type $type): Type
    {
        return Any::of($this, $type);
    }

    public function constraint(Clock $clock): Constraint
    {
        return $this->type->constraint($clock)->or(Is::null());
    }

    public function toArray(): array
    {
        $type = $this->type->toArray();
        $type['nullable'] = true;

        return $type;
    }
}
