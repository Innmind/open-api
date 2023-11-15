<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Response;

use Innmind\OpenAPI\Type\{
    Shape,
    Sequence,
    Str,
    Uuid,
    Password,
    Url,
    Date,
    DateTime,
    File,
    Integer,
    Number,
};
use Innmind\MediaType\MediaType;
use Innmind\Immutable\Map;

final class Definition
{
    private MediaType $mediaType;
    private Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $schema;
    private ?string $description;
    /** @var Map<non-empty-string, Str> */
    private Map $headers;

    /**
     * @psalm-mutation-free
     *
     * @param Map<non-empty-string, Str> $headers
     */
    private function __construct(
        MediaType $mediaType,
        Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $schema,
        ?string $description,
        Map $headers,
    ) {
        $this->mediaType = $mediaType;
        $this->schema = $schema;
        $this->description = $description;
        $this->headers = $headers;
    }

    /**
     * @psalm-pure
     */
    public static function of(
        MediaType $mediaType,
        Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $schema,
        ?string $description,
    ): self {
        return new self($mediaType, $schema, $description, Map::of());
    }

    /**
     * @psalm-mutation-free
     *
     * @param non-empty-string $name
     */
    public function withHeader(string $name, Str $schema): self
    {
        return new self(
            $this->mediaType,
            $this->schema,
            $this->description,
            ($this->headers)($name, $schema),
        );
    }

    public function toArray(): array
    {
        $response = [
            'content' => [
                $this->mediaType->toString() => [
                    'schema' => $this->schema->toArray(),
                ],
            ],
        ];

        if (\is_string($this->description)) {
            $response['description'] = $this->description;
        }

        if (!$this->headers->empty()) {
            $response['headers'] = \array_merge(
                ...$this
                    ->headers
                    ->map(static fn($name, $schema) => [
                        $name => [
                            'schema' => $schema->toArray(),
                        ],
                    ])
                    ->values()
                    ->toList(),
            );
        }

        return $response;
    }
}
