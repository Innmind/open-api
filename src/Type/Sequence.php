<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\OpenAPI\Schema;
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
    private Schema|self|Shape|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $items;
    private ?string $title;
    private ?string $description;
    private ?array $example;
    private bool $nullable;

    private function __construct(
        Schema|self|Shape|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $items,
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
        Schema|self|Shape|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $items,
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

    public function toArray(): array
    {
        $type = [
            'type' => 'integer',
            'items' => match (true) {
                $this->items instanceof Schema => [
                    '$ref' => "#/components/schemas/{$this->items->name()}",
                ],
                default => $this->items->toArray(),
            },
        ];

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
