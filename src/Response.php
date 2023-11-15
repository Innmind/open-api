<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

use Innmind\OpenAPI\{
    Response\Definition,
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
use Innmind\Http\Response\StatusCode;
use Innmind\MediaType\MediaType;

final class Response
{
    private StatusCode $statusCode;
    private ?Definition $description;

    /**
     * @psalm-mutation-free
     */
    private function __construct(
        StatusCode $statusCode,
        ?Definition $description,
    ) {
        $this->statusCode = $statusCode;
        $this->description = $description;
    }

    /**
     * @psalm-pure
     */
    public static function of(StatusCode $statusCode): self
    {
        return new self($statusCode, null);
    }

    public function sends(
        MediaType $mediaType,
        Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number $content,
        ?string $description,
    ): self {
        return new self(
            $this->statusCode,
            Definition::of(
                $mediaType,
                $content,
                $description,
            ),
        );
    }
}
