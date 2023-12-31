<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\OpenAPI\{
    Schema,
    Type,
};
use Innmind\TimeContinuum\Clock;
use Innmind\Validation\{
    Constraint,
    Shape as VShape,
    Is,
};
use Innmind\Immutable\{
    Sequence as Seq,
    Map as IMap,
};

/**
 * This represents an object (but named shape as object is a reserved keyword)
 *
 * @psalm-immutable
 * @implements Type<array<non-empty-string, mixed>>
 */
final class Shape implements Type
{
    private ?string $title;
    private ?string $description;
    /** @var Seq<non-empty-string> */
    private Seq $required;
    /** @var IMap<non-empty-string, Schema|Type> */
    private IMap $properties;
    private ?array $example;

    /**
     * @param Seq<non-empty-string> $required
     * @param IMap<non-empty-string, Schema|Type> $properties
     */
    private function __construct(
        ?string $title,
        ?string $description,
        Seq $required,
        IMap $properties,
        ?array $example,
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->required = $required;
        $this->properties = $properties;
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
            Seq::of(),
            IMap::of(),
            null,
        );
    }

    /**
     * @param non-empty-string $name
     */
    public function property(string $name, Schema|Type $schema): self
    {
        return new self(
            $this->title,
            $this->description,
            $this->required,
            ($this->properties)($name, $schema),
            $this->example,
        );
    }

    /**
     * @param non-empty-string $name
     */
    public function require(string $name): self
    {
        return new self(
            $this->title,
            $this->description,
            ($this->required)($name),
            $this->properties,
            $this->example,
        );
    }

    public function example(array $example): self
    {
        return new self(
            $this->title,
            $this->description,
            $this->required,
            $this->properties,
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
        $constraint = $this
            ->properties
            ->find(static fn() => true)
            ->match(
                fn($pair) => $this->properties->reduce(
                    VShape::of(
                        $pair->key(),
                        $this->keyConstraint(
                            $clock,
                            $pair->key(),
                            $pair->value(),
                        ),
                    ),
                    fn(VShape $shape, $name, $type) => $shape->with(
                        $name,
                        $this->keyConstraint($clock, $name, $type),
                    ),
                ),
                static fn() => Is::array(),
            );

        /** @psalm-suppress InvalidArgument */
        return $this
            ->properties
            ->keys()
            ->exclude(fn($key) => $this->required->contains($key))
            ->reduce(
                $constraint,
                static fn(VShape $constraint, $key) => $constraint->optional($key),
            );
    }

    public function toArray(): array
    {
        $type = ['type' => 'object'];

        if (!$this->required->empty()) {
            $type['required'] = $this->required->toList();
        }

        if (!$this->properties->empty()) {
            $type['properties'] = \array_merge(
                ...$this
                    ->properties
                    ->map(static fn($name, $type) => match (true) {
                        $type instanceof Schema => [$name => [
                            '$ref' => "#/components/schemas/{$type->name()}",
                        ]],
                        default => [$name => $type->toArray()],
                    })
                    ->values()
                    ->toList(),
            );
        }

        if (\is_string($this->title)) {
            $type['title'] = $this->title;
        }

        if (\is_string($this->description)) {
            $type['description'] = $this->description;
        }

        if (\is_array($this->example)) {
            $type['example'] = $this->example;
        }

        return $type;
    }

    /**
     * @param non-empty-string $name
     */
    private function keyConstraint(
        Clock $clock,
        string $name,
        Schema|Type $type,
    ): Constraint {
        if ($type instanceof Schema) {
            $type = $type->type();
        }

        return $type->constraint($clock);
    }
}
