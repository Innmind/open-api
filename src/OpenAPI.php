<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

use Innmind\Url\Url;
use Innmind\Immutable\Sequence;

/**
 * @psalm-immutable
 */
enum OpenAPI
{
    case v3;

    /**
     * @param non-empty-string $title
     * @param non-empty-string $version
     */
    public function info(
        string $title,
        string $version,
        string $description = null,
    ): Document {
        return Document::of($this)->info($title, $version, $description);
    }

    public function server(Url $server, string $description = null): Document
    {
        return Document::of($this)->server($server, $description);
    }

    public function tags(Tag $tag, Tag ...$tags): Document
    {
        return Document::of($this)->tags($tag, ...$tags);
    }

    public function securitySchemes(
        SecurityScheme $scheme,
        SecurityScheme ...$schemes,
    ): Document {
        return Document::of($this)->securitySchemes($scheme, ...$schemes);
    }

    /**
     * @param Sequence<Path> $paths
     */
    public function paths(Sequence $paths): Document
    {
        return Document::of($this)->paths($paths);
    }

    /**
     * @return non-empty-string
     */
    public function toString(): string
    {
        return '3.1.0';
    }
}
