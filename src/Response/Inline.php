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

final class Inline
{
    private MediaType $mediaType;
    private Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $content;
    private ?string $description;

    /**
     * @psalm-mutation-free
     */
    private function __construct(
        MediaType $mediaType,
        Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $content,
        ?string $description,
    ) {
        $this->mediaType = $mediaType;
        $this->content = $content;
        $this->description = $description;
    }

    /**
     * @psalm-pure
     */
    public static function of(
        MediaType $mediaType,
        Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $content,
        ?string $description,
    ): self {
        return new self($mediaType, $content, $description);
    }
}
