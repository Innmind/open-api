<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\OpenAPI\Schema;
use Innmind\Immutable\{
    Sequence,
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
    /** @var Sequence<non-empty-string> */
    private Sequence $required;
    /** @var Map<non-empty-string, Schema|self|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number> */
    private Map $properties;
    private ?array $example;
    private bool $nullable;

    /**
     * @param Sequence<non-empty-string> $required
     * @param Map<non-empty-string, Schema|self|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number> $properties
     */
    private function __construct(
        ?string $title,
        ?string $description,
        Sequence $required,
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
            Sequence::of(),
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
}
