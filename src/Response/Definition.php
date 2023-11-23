<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Response;

use Innmind\OpenAPI\{
    Schema,
    MediaType,
    Type\Shape,
    Type\Sequence,
    Type\Str,
    Type\Uuid,
    Type\Password,
    Type\Url,
    Type\Date,
    Type\DateTime,
    Type\File,
    Type\Integer,
    Type\Number,
};
use Innmind\Immutable\Map;

final class Definition
{
    private MediaType $mediaType;
    private Schema|Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $schema;
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
        Schema|Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $schema,
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
        Schema|Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $schema,
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
                    'schema' => match (true) {
                        $this->schema instanceof Schema => [
                            '$ref' => "#/components/schemas/{$this->schema->name()}",
                        ],
                        default => $this->schema->toArray(),
                    },
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
