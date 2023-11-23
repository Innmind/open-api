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
 * @implements Type<float>
 */
final class Number implements Type
{
    private ?string $title;
    private ?string $description;
    private ?float $example;

    private function __construct(
        ?string $title,
        ?string $description,
        ?float $example,
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->example = $example;
    }

    /**
     * @psalm-pure
     */
    public static function of(string $title = null, string $description = null): self
    {
        return new self(
            $title,
            $description,
            null,
        );
    }

    public function example(float $example): self
    {
        return new self(
            $this->title,
            $this->description,
            $example,
        );
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
        return Is::float();
    }

    public function toArray(): array
    {
        $type = ['type' => 'number'];

        if (\is_string($this->title)) {
            $type['title'] = $this->title;
        }

        if (\is_string($this->description)) {
            $type['description'] = $this->description;
        }

        if (\is_float($this->example)) {
            $type['example'] = $this->example;
        }

        return $type;
    }
}
