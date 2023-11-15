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
    private Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $content;
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
        Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $content,
        ?string $description,
        Map $headers,
    ) {
        $this->mediaType = $mediaType;
        $this->content = $content;
        $this->description = $description;
        $this->headers = $headers;
    }

    /**
     * @psalm-pure
     */
    public static function of(
        MediaType $mediaType,
        Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $content,
        ?string $description,
    ): self {
        return new self($mediaType, $content, $description, Map::of());
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
            $this->content,
            $this->description,
            ($this->headers)($name, $schema),
        );
    }
}
