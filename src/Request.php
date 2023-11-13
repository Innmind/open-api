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
    private ?array $example;

    /**
     * @psalm-mutation-free
     */
    private function __construct(
        MediaType $mediaType,
        Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $schema,
        ?array $example,
    ) {
        $this->mediaType = $mediaType;
        $this->schema = $schema;
        $this->example = $example;
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
            null,
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function example(array $example): self
    {
        return new self(
            $this->mediaType,
            $this->schema,
            $example,
        );
    }
}
