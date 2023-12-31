<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

use Innmind\OpenAPI\{
    Response\Definition,
    Response\Reference,
    Type,
    Type\Str,
};
use Innmind\Http\Response\StatusCode;

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

    /**
     * @psalm-mutation-free
     */
    public function sends(
        MediaType $mediaType,
        Schema|Type $content,
        string $description = null,
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

    /**
     * @psalm-mutation-free
     *
     * @param non-empty-string $name
     */
    public function withHeader(string $name, Str $schema): self
    {
        return match (true) {
            $this->description instanceof Definition => new self(
                $this->statusCode,
                $this->description->withHeader($name, $schema),
            ),
            default => $this,
        };
    }

    /**
     * @psalm-mutation-free
     */
    public function references(Reference $reference): self
    {
        return new self($this->statusCode, $reference);
    }

    public function statusCode(): StatusCode
    {
        return $this->statusCode;
    }

    public function toArray(): array
    {
        return match (true) {
            \is_null($this->description) => [],
            $this->description instanceof Reference => [
                '$ref' => "#/components/responses/{$this->description->name()}",
            ],
            default => $this->description->toArray(),
        };
    }
}
