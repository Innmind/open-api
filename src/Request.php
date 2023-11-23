<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

/**
 * @template T
 */
final class Request
{
    private MediaType $mediaType;
    /** @var Type<T> */
    private Type $schema;

    /**
     * @psalm-mutation-free
     *
     * @param Type<T> $schema
     */
    private function __construct(
        MediaType $mediaType,
        Type $schema,
    ) {
        $this->mediaType = $mediaType;
        $this->schema = $schema;
    }

    /**
     * @psalm-pure
     * @template A
     *
     * @param Type<A> $schema
     *
     * @return self<A>
     */
    public static function of(
        MediaType $mediaType,
        Type $schema,
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
