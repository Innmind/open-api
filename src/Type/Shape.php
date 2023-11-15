<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\OpenAPI\Schema;
use Innmind\Immutable\{
    Sequence as Seq,
    Map,
};

/**
 * This represents an object (but named shape as object is a reserved keyword)
 *
 * @psalm-immutable
 */
final class Shape
{
    private ?string $title;
    private ?string $description;
    /** @var Seq<non-empty-string> */
    private Seq $required;
    /** @var Map<non-empty-string, Schema|self|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number> */
    private Map $properties;
    private ?array $example;
    private bool $nullable;

    /**
     * @param Seq<non-empty-string> $required
     * @param Map<non-empty-string, Schema|self|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number> $properties
     */
    private function __construct(
        ?string $title,
        ?string $description,
        Seq $required,
        Map $properties,
        ?array $example,
        bool $nullable,
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->required = $required;
        $this->properties = $properties;
        $this->example = $example;
        $this->nullable = $nullable;
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
            Map::of(),
            null,
            false,
        );
    }

    /**
     * @param non-empty-string $name
     */
    public function property(string $name, Schema|self|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $schema): self
    {
        return new self(
            $this->title,
            $this->description,
            $this->required,
            ($this->properties)($name, $schema),
            $this->example,
            $this->nullable,
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
            $this->nullable,
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
            $this->nullable,
        );
    }

    public function nullable(): self
    {
        return new self(
            $this->title,
            $this->description,
            $this->required,
            $this->properties,
            $this->example,
            true,
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
                        default => [$name, $type->toArray()],
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

        if ($this->nullable) {
            $type['nullable'] = $this->nullable;
        }

        return $type;
    }
}
