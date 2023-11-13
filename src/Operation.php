<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

use Innmind\Http\Method;
use Innmind\Immutable\Sequence;

final class Operation
{
    private Method $method;
    /** @var ?non-empty-string */
    private ?string $id;
    private ?string $summary;
    private ?string $description;
    /** @var Sequence<Tag> */
    private Sequence $tags;
    /** @var Sequence<SecurityScheme> */
    private Sequence $securitySchemes;

    /**
     * @psalm-mutation-free
     *
     * @param ?non-empty-string $id
     * @param Sequence<Tag> $tags
     * @param Sequence<SecurityScheme> $securitySchemes
     */
    private function __construct(
        Method $method,
        ?string $summary,
        ?string $description,
        ?string $id,
        Sequence $tags,
        Sequence $securitySchemes,
    ) {
        $this->method = $method;
        $this->id = $id;
        $this->summary = $summary;
        $this->description = $description;
        $this->tags = $tags;
        $this->securitySchemes = $securitySchemes;
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function get(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::get,
            $summary,
            $description,
            $id,
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function put(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::put,
            $summary,
            $description,
            $id,
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function post(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::post,
            $summary,
            $description,
            $id,
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function delete(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::delete,
            $summary,
            $description,
            $id,
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function options(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::options,
            $summary,
            $description,
            $id,
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function head(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::head,
            $summary,
            $description,
            $id,
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function patch(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::patch,
            $summary,
            $description,
            $id,
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function trace(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::trace,
            $summary,
            $description,
            $id,
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function tags(Tag $tag, Tag ...$tags): self
    {
        return new self(
            $this->method,
            $this->summary,
            $this->description,
            $this->id,
            $this->tags->append(Sequence::of($tag, ...$tags)),
            $this->securitySchemes,
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function securedBy(
        SecurityScheme $scheme,
        SecurityScheme ...$schemes,
    ): self {
        return new self(
            $this->method,
            $this->summary,
            $this->description,
            $this->id,
            $this->tags,
            $this->securitySchemes->append(Sequence::of($scheme, ...$schemes)),
        );
    }
}
