<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\OpenAPI\Type;
use Innmind\TimeContinuum\Clock;
use Innmind\Validation\{
    Constraint,
    Failure,
    Of,
};
use Innmind\Immutable\Validation;
use Ramsey\Uuid\Uuid as RUuid;

/**
 * @psalm-immutable
 * @implements Type<string>
 */
final class Uuid implements Type
{
    private Str $type;

    private function __construct(Str $type)
    {
        $this->type = $type;
    }

    /**
     * @psalm-pure
     */
    public static function of(string $title = null, string $description = null): self
    {
        return new self(Str::of(
            $title,
            $description,
        ));
    }

    public function example(string $example): self
    {
        return new self($this->type->example($example));
    }

    public function nullable(): Nullable
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

    public function constraint(Clock $clock): Constraint
    {
        return $this->type->constraint($clock)->and(Of::callable(
            static fn(string $string) => match (RUuid::isValid($string)) {
                true => Validation::success($string),
                false => Validation::fail(Failure::of('String is not a valid uuid')),
            },
        ));
    }

    public function toArray(): array
    {
        $type = $this->type->toArray();
        $type['format'] = 'uuid';

        return $type;
    }
}
