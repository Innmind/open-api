<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\Immutable\{
    Sequence as Seq,
    Map,
};

/**
 * This represents an array (but named sequence as array is a reserved keyword)
 *
 * @psalm-immutable
 */
final class Sequence
{
    private self|Shape|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $items;
    private ?string $title;
    private ?string $description;
    private ?array $example;
    private bool $nullable;

    private function __construct(
        self|Shape|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $items,
        ?string $title,
        ?string $description,
        ?array $example,
        bool $nullable,
    ) {
        $this->items = $items;
        $this->title = $title;
        $this->description = $description;
        $this->example = $example;
        $this->nullable = $nullable;
    }

    /**
     * @psalm-pure
     */
    public static function of(
        self|Shape|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $items,
        string $title = null,
        string $description = null,
    ): self {
        return new self(
            $items,
            $title,
            $description,
            null,
            false,
        );
    }

    public function example(array $example): self
    {
        return new self(
            $this->items,
            $this->title,
            $this->description,
            $example,
            $this->nullable,
        );
    }

    public function nullable(): self
    {
        return new self(
            $this->items,
            $this->title,
            $this->description,
            $this->example,
            true,
        );
    }
}
