<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\OpenAPI\{
    Schema,
    Type,
};
use Innmind\Immutable\{
    Sequence as Seq,
    Map,
};

/**
 * This represents an array (but named sequence as array is a reserved keyword)
 *
 * @psalm-immutable
 */
final class Sequence implements Type
{
    private Schema|Type $items;
    private ?string $title;
    private ?string $description;
    private ?array $example;

    private function __construct(
        Schema|Type $items,
        ?string $title,
        ?string $description,
        ?array $example,
    ) {
        $this->items = $items;
        $this->title = $title;
        $this->description = $description;
        $this->example = $example;
    }

    /**
     * @psalm-pure
     */
    public static function of(
        Schema|Type $items,
        string $title = null,
        string $description = null,
    ): self {
        return new self(
            $items,
            $title,
            $description,
            null,
        );
    }

    public function example(array $example): self
    {
        return new self(
            $this->items,
            $this->title,
            $this->description,
            $example,
        );
    }

    public function nullable(): Nullable
    {
        return Nullable::of($this);
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

        return $type;
    }
}
