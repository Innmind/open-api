<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

use Innmind\OpenAPI\{
    Response\Definition,
    Response\Reference,
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
    private Definition|Reference|null $description;

    /**
     * @psalm-mutation-free
     */
    private function __construct(
        StatusCode $statusCode,
        Definition|Reference|null $description,
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

    public function references(Reference $reference): self
    {
        return new self($this->statusCode, $reference);
    }

    public function toArray(): array
    {
        $response = match (true) {
            \is_null($this->description) => [],
            $this->description instanceof Reference => [
                '$ref' => "#/components/responses/{$this->description->name()}",
            ],
            default => $this->description->toArray(),
        };

        return [$this->statusCode->toInt() => $response];
    }
}
