<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

use Innmind\Url\Url;
use Innmind\Immutable\Sequence;

final class Document
{
    private OpenAPI $version;
    private ?Info $info;
    /** @var Sequence<array{Url, ?string}> */
    private Sequence $servers;
    /** @var Sequence<Tag> */
    private Sequence $tags;
    /** @var Sequence<SecurityScheme> */
    private Sequence $securitySchemes;

    /**
     * @psalm-mutation-free
     *
     * @param Sequence<array{Url, ?string}> $servers
     * @param Sequence<Tag> $tags
     * @param Sequence<SecurityScheme> $securitySchemes
     */
    private function __construct(
        OpenAPI $version,
        ?Info $info,
        Sequence $servers,
        Sequence $tags,
        Sequence $securitySchemes,
    ) {
        $this->version = $version;
        $this->info = $info;
        $this->servers = $servers;
        $this->tags = $tags;
        $this->securitySchemes = $securitySchemes;
    }

    /**
     * @psalm-pure
     */
    public static function of(OpenAPI $version): self
    {
        return new self(
            $version,
            null,
            Sequence::of(),
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-mutation-free
     *
     * @param non-empty-string $title
     * @param non-empty-string $version
     */
    public function info(
        string $title,
        string $version,
        string $description = null,
    ): self {
        return new self(
            $this->version,
            Info::of($title, $version, $description),
            $this->servers,
            $this->tags,
            $this->securitySchemes,
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function server(Url $server, string $description = null): self
    {
        return new self(
            $this->version,
            $this->info,
            ($this->servers)([$server, $description]),
            $this->tags,
            $this->securitySchemes,
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function tags(Tag $tag, Tag ...$tags): self
    {
        return new self(
            $this->version,
            $this->info,
            $this->servers,
            $this->tags->append(Sequence::of($tag, ...$tags)),
            $this->securitySchemes,
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function securitySchemes(
        SecurityScheme $scheme,
        SecurityScheme ...$schemes,
    ): self {
        return new self(
            $this->version,
            $this->info,
            $this->servers,
            $this->tags,
            $this->securitySchemes->append(Sequence::of($scheme, ...$schemes)),
        );
    }
}
