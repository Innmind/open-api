<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

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

final class Request
{
    private MediaType $mediaType;
    private Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $schema;

    /**
     * @psalm-mutation-free
     */
    private function __construct(
        MediaType $mediaType,
        Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $schema,
    ) {
        $this->mediaType = $mediaType;
        $this->schema = $schema;
    }

    /**
     * @psalm-pure
     */
    public static function of(
        MediaType $mediaType,
        Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $schema,
    ): self {
        return new self(
            $mediaType,
            $schema,
        );
    }

    public function toArray(): array
    {
        return [$this->mediaType->toString() => $this->schema->toArray()];
    }
}
