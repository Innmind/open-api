<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

/**
 * @psalm-immutable
 */
final class Integer
{
    private ?string $title;
    private ?string $description;
    private ?int $example;
    private bool $nullable;

    private function __construct(
        ?string $title,
        ?string $description,
        ?int $example,
        bool $nullable,
    ) {
        $this->title = $title;
        $this->description = $description;
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
            null,
            false,
        );
    }

    public function example(int $example): self
    {
        return new self(
            $this->title,
            $this->description,
            $example,
            $this->nullable,
        );
    }

    public function nullable(): self
    {
        return new self(
            $this->title,
            $this->description,
            $this->example,
            true,
        );
    }
}
