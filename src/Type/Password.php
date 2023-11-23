<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\OpenAPI\Type;
use Innmind\TimeContinuum\Clock;
use Innmind\Validation\Constraint;

/**
 * @psalm-immutable
 * @implements Type<string>
 */
final class Password implements Type
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

    public function or(Type $type): Type
    {
        return Any::of($this, $type);
    }

    public function constraint(Clock $clock): Constraint
    {
        return $this->type->constraint($clock);
    }

    public function toArray(): array
    {
        $type = $this->type->toArray();
        $type['format'] = 'password';

        return $type;
    }
}
