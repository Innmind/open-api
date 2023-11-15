<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\Immutable\RegExp;

/**
 * @psalm-immutable
 */
final class Str
{
    private ?string $title;
    private ?string $description;
    private ?string $example;
    private bool $nullable;
    private ?RegExp $pattern;

    private function __construct(
        ?string $title,
        ?string $description,
        ?string $example,
        bool $nullable,
        ?RegExp $pattern,
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->example = $example;
        $this->nullable = $nullable;
        $this->pattern = $pattern;
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
            false,
            null,
        );
    }

    public function example(string $example): self
    {
        return new self(
            $this->title,
            $this->description,
            $example,
            $this->nullable,
            $this->pattern,
        );
    }

    public function nullable(): self
    {
        return new self(
            $this->title,
            $this->description,
            $this->example,
            true,
            $this->pattern,
        );
    }

    public function restrictVia(RegExp $pattern): self
    {
        return new self(
            $this->title,
            $this->description,
            $this->example,
            $this->nullable,
            $pattern,
        );
    }
}
